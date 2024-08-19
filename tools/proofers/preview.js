/* exported makePreview */
/* exported analyse */
/* exported processExMath */
/* exported defaultStyles */
/* global XRegExp */

// The formatting rules are applied as if proofers' notes were
// invisible so remove them first and save for later. But first check if they
// are malformed, if so mark the problem and do nothing else.

// Then analyse the text without notes and make an array of issues.
// html encode the notes.
// The issues and the notes are then merged back into the text.

// The last stage is to interpret any tags to show the styling.
// The final text should not contain any invalid html which would cause the
// browser to leave it out.

// these are the default values. If the user changes anything the new
// styles are saved in local storage and reloaded next time.
// the foreground and background colours for plain text, italic, bold,
// gesperrt, smallcaps, font change, other tags, highlighting issues
// and possible issues.
// An empty color string means use default color
const defaultStyles = {
    t: {bg: "#fffcf4", fg: "#000000"},
    i: {bg: "", fg: "#0000ff"},
    b: {bg: "", fg: "#c55a1b"},
    g: {bg: "", fg: "#8a2be2"},
    sc: {bg: "", fg: "#009700"},
    f: {bg: "", fg: "#ff0000"},
    u: {bg: "", fg: ""},
    etc: {bg: "#ffcaaf", fg: ""},
    err: {bg: "#ff0000", fg: ""},
    hlt: {bg: "#ceff09", fg: ""},
    blockquote: {bg: "#fecafe", fg: ""},
    nowrap: {bg: "#d1fcff", fg: ""},
    color: true, // colour the markup or not
    allowUnderline: false,
    defFontIndex: 0,
    suppress: {},
    initialViewMode: "no_tags",
    allowMathPreview: false
};

// processes the text by textFunction but in math mode only outside math markup
// define this at top level so we can test it
function processExMath(text, textFunction, allowMath) {
    if(!allowMath) {
        return textFunction(text);
    } else {
        // find whole math strings \[ ... \] or \( ... \)
        let txtOut = "";
        let mathRegex = /\\\[[^]*?\\\]|\\\([^]*?\\\)/g;
        let result;
        let startIndex = 0;
        while((result = mathRegex.exec(text)) !== null) {
            txtOut += textFunction(text.slice(startIndex, result.index)) + result[0];
            startIndex = mathRegex.lastIndex;
        }
        // no more found, process to end
        txtOut += textFunction(text.slice(startIndex));
        return txtOut;
    }
}

// find index of next unmatched ] return 0 if none found
const re = /\[|\]/g;  // [ or ]
function findClose(txt, index) {
    let result;
    let nestLevel = 0;
    re.lastIndex = index;
    while ((result = re.exec(txt)) !== null) {
        if ("[" === result[0]) {
            nestLevel += 1;
        } else { // must be ]
            if (0 === nestLevel) {
                return result.index;
            }
            nestLevel -= 1;
        }
    }
    return 0;
}

function removeTrail(txt) {
    // Remove trailing whitespace on each line and trailing blank lines
    txt = txt.replace(/ *$/mg, "");
    txt = txt.replace(/\s*$/, "");
    return txt;
}

const getILTags = function(configuration) {
    let ILTags = "[ibfg]|sc";
    if (configuration.allowUnderline) {
        ILTags += "|u";
    }
    return ILTags;
};

const analyse = function (txt, config) {
// the default issue types, can be over-ridden
// 1 means a definite issue, 0 a possible issue
    const ILTags = getILTags(config);
    var issueType = {
        noStartTag: 1,
        noEndTag: 1,
        noEndTagInPara: 1,
        misMatchTag: 1,
        nestedTag: 1,
        unRecTag: 0,
        charBefore: 1,
        blankBefore: 1,
        blankAfter: 0,
        NWinNW: 1,
        BQinNW: 1,
        charAfter: 1,
        OolPrev: 1,
        OolNext: 1,
        blankLines124: 1,
        puncAfterStart: 0,
        spaceAfterStart: 1,
        nlAfterStart: 1,
        nlBeforeEnd: 1,
        spaceBeforeEnd: 1,
        noBold: 1,
        scNoCap: 1,
        charBeforeStart: 0,
        charAfterEnd: 0,
        puncBEnd: 0,
        noCloseBrack: 0,
        footnoteId: 0,
        starAnchor: 0,
        noFootnote: 0,
        noAnchor: 0,
        noColon: 0,
        colonNext: 0,
        spaceNext: 0,
        dupNote: 0,
        continueFirst: 0,
        emptyTag: 1,
        multipleAnchors: 0,
        boldPara: 0,
    };

    var issArray = []; // stores issues for markup-insertion later

    // this records issues which could prevent checkSC and testBoldBlock from
    // working. Not all definite issues mean bad parse
    let parseOK = true;
    function badParse() {
        parseOK = false;
    }

    // make an entry in issArray. start, len: position in text to mark
    // code: issue, optional type overrides issueType,
    // subText is text to substitute in some messages
    function reportIssue(start, len, code, type = null, subText = "") {
        if (!(config.suppress[code])) {
            if(type == null) {
                type = issueType[code];
            }
            issArray.push({start: start, len: len, code: code, type: type, subText: subText});
        }
    }

    const reNoteStart = /\[\*\*/g;

    function checkProoferNotes() {
        // look for [** then look for ] skipping matched [ ]
        let result, closeIndex;
        while(null !== (result = reNoteStart.exec(txt))) {
            closeIndex = findClose(txt, reNoteStart.lastIndex);
            if (0 === closeIndex) {
                // no ] found
                reportIssue(result.index, 3, "noCloseBrack", 1);
                badParse();
                return;
            } else {
                // continue search from the ] we found
                reNoteStart.lastIndex = closeIndex;
            }
        }
    }

    let notes = [];

    // make text with notes removed, put them in an array with indexes into the new text
    function removeAllNotes(txt) {
        // Remove trailing whitespace on each line so whole line notes
        // are not disguised.
        txt = removeTrail(txt);

        let beginIndex = 0;
        let txtOut = "";
        let result, noteStartIndex, noteEndIndex;
        // start of note relative to txtOut
        let outNoteStart = 0;
        const maxIndex = txt.length - 1;
        reNoteStart.lastIndex = 0;
        // we've already checked that notes are correctly terminated
        // find start of note
        while (null != (result = reNoteStart.exec(txt))) {
            noteStartIndex = result.index;
            noteEndIndex = findClose(txt, reNoteStart.lastIndex);
            // copy text to start of note
            txtOut += txt.slice(beginIndex, noteStartIndex);
            outNoteStart += noteStartIndex - beginIndex;
            // if note starts at beginning of a line and ends at the end of a line
            // remove any following nl character also so doesn't count as a blank line and include the nl in the note
            if(((noteStartIndex === 0) || (txt.charAt(noteStartIndex - 1) === "\n"))
                && ((noteEndIndex === maxIndex) || (txt.charAt(noteEndIndex + 1) === "\n"))) {
                // let next copy begin after the ending \n
                beginIndex = noteEndIndex + 2;
            } else {
                // let next copy begin after the note
                beginIndex = noteEndIndex + 1;
            }
            notes.push({start: outNoteStart, text: txt.slice(noteStartIndex, beginIndex)});
            reNoteStart.lastIndex = beginIndex;
        }
        // copy any remaining text
        // If beginIndex is greater than or equal to str.length, an empty string is returned.
        txtOut += txt.slice(beginIndex);

        return txtOut;
    }

    // find end of line (or eot) following ix
    function findEnd(ix) {
        var re = /\n|$/g;
        var result;
        re.lastIndex = ix;
        result = re.exec(txt);
        return result.index;
    }

    function chkCharAfter(start, len, type, descriptor) {
        const ix = start + len;
        const end = findEnd(ix);
        if (/\S/.test(txt.slice(ix, end))) {
            reportIssue(start, len, "charAfter", type, descriptor);
            return true;
        }
        return false;
    }

    // the parsers for inline and out-of-line tags work with a stack:
    // for correct nesting opening tags are pushed onto the stack and popped off
    // when a corresponding closing tag is found

    // parse the out-of-line tags
    // cases for tag on stack top:
    // none: /* or /# -> push, else error
    // /*: */ pop, #/ -> mismatch, /# -> BQ not allowed inside NW, /* -> NW inside NW
    // /#: /# or /* -> push, #/ -> pop, */ mismatch
    function parseOol() {
        var tagStack = [];  // holds start tag /* or /# and index
        var start;
        var tagString;
        var stackTop;
        var result;
        var oolre = /\/\*|\/#|\*\/|#\//g;   // any out-of-line tag
        var prevLin;

        // find previous line, assume ix > 0
        function findPrevLine(ix) {
            var pStart = ix - 1;
            while (pStart > 0) {
                pStart -= 1;
                if (txt.charAt(pStart) === "\n") {
                    pStart += 1;
                    break;
                }
            }
            return txt.slice(pStart, ix - 1);
        }

        // check that no other characters are on the same line
        function chkAlone(start, len, descriptor) {
            if(chkCharAfter(start, len, 1, descriptor)) {
                return;
            }

            if (/./.test(txt.charAt(start - 1))) {
                reportIssue(start, len, "charBefore");
            }
        }

        while ((result = oolre.exec(txt)) !== null) {
            start = result.index;
            tagString = result[0];

            chkAlone(start, 2, tagString);
            // for an opening tag check previous line is blank
            // or an opening block quote tag
            // allow also an opening no-wrap to avoid giving a misleading message
            // that it is "normal text". The error will be caught elsewhere.
            if ((tagString.charAt(0) === "/") && (start > 1) && (txt.charAt(start - 2) !== "\n")) {
                prevLin = findPrevLine(start);
                if (!(("/#" === prevLin) || ("/*" === prevLin))) {
                    reportIssue(start, 2, "OolPrev");
                }
            }
            // for a closing tag check following line is blank
            // or a closing block quote or ] (ending a footnote).
            // allow also closing no-wrap to avoid misleading error message
            if (tagString.charAt(1) === "/") {
                if (/[^#*\n\]]/.test(txt.charAt(findEnd(start + 2) + 1))) {
                    reportIssue(start, 2, "OolNext");
                }
            }

            if (tagStack.length === 0) {
                if ('/' === tagString.charAt(0)) {     // start tag
                    tagStack.push({tag: tagString, start: start});
                } else {
                    reportIssue(start, 2, "noStartTag");
                }
            } else {    // there are tags in the stack
                stackTop = tagStack[tagStack.length - 1];
                if (stackTop.tag.charAt(1) === "*") {  // open NW;
                    switch (tagString) {
                    case "*/":  // close NW ok
                        tagStack.pop();
                        break;
                    case "#/": // close BQ
                        tagStack.pop();
                        reportIssue(start, 2, "misMatchTag");
                        reportIssue(stackTop.start, 2, "misMatchTag");
                        break;
                    case "/*": // open NW
                        reportIssue(start, 2, "NWinNW");
                        tagStack.push({tag: tagString, start: start});
                        break;
                    default:    // open BQ
                        reportIssue(start, 2, "BQinNW");
                        tagStack.push({tag: tagString, start: start});
                        break;
                    }
                } else {    // top of stack is /#
                    switch (tagString) {
                    case "#/": // close BQ
                        tagStack.pop();
                        break;
                    case "*/":  // close NW
                        tagStack.pop();
                        reportIssue(start, 2, "misMatchTag");
                        reportIssue(stackTop.start, 2, "misMatchTag");
                        break;
                    default:    // open either
                        tagStack.push({tag: tagString, start: start});
                        break;
                    }
                }
            }
        }
        // if there are any tags on the stack mark them as errors
        while (tagStack.length !== 0) {
            stackTop = tagStack.pop();
            reportIssue(stackTop.start, 2, "noEndTag");
        }
    }

    // if find a start tag, check if any same already in stack, push onto stack
    // if end tag, check it matches stack top, pop else error
    // if none found, if stack empty finished else error
    function parseInLine() {
        var tagString;
        var end = 0;
        var tagLen;
        var start = 0;
        var tagStack = [];
        var stackTop;
        var preChar;
        var postChar;

        // find index in stack of ntag, return -1 if none found
        function match(tagData) {
            return tagData.tag === tagString;
        }

        // regex to match < and next up to 4 chars, or a blank line
        const rePossTag = new RegExp("<.{0,4}|\\n\\n", "g");
        const reGoodTag = new RegExp(`^<(/?)(${ILTags})>`);

        let possTagResult;
        while ((possTagResult = rePossTag.exec(txt)) !== null) {
            if (possTagResult[0] === "\n\n") {
                while (tagStack.length !== 0) {
                    stackTop = tagStack.pop();
                    reportIssue(stackTop.start, stackTop.tagLen, "noEndTagInPara");
                    badParse();
                }
                continue;
            }
            const possibleTag = possTagResult[0];
            if(possibleTag.match(/^<tb>/)) {
                continue;
            }
            const goodTagResult = possibleTag.match(reGoodTag);
            start = possTagResult.index;
            if(!goodTagResult) {
                reportIssue(start, 1, "unRecTag");
                // start next search after < in case another < follows
                rePossTag.lastIndex = start + 1;
                continue;
            }
            tagLen = goodTagResult[0].length;
            end = start + tagLen;
            // start next search after goodTag
            rePossTag.lastIndex = end;
            tagString = goodTagResult[2];
            preChar = txt.charAt(start - 1);
            postChar = txt.charAt(end);
            if (goodTagResult[1] === '/') {    // end tag
                // check for , ; or : before end tag except at end of text
                if (/[,;:]/.test(preChar) && (txt.length !== end)) {
                    reportIssue(start - 1, 1, "puncBEnd");
                }
                if (preChar === " ") {
                    reportIssue(start - 1, 1, "spaceBeforeEnd");
                }
                if (preChar === "\n") {
                    reportIssue(start, tagLen, "nlBeforeEnd");
                }
                // letter or number after end tag
                if (XRegExp("\\pL|\\pN", "Ag").test(postChar)) {
                    reportIssue(end, 1, "charAfterEnd");
                }
                if (tagStack.length === 0) {    // missing start tag
                    reportIssue(start, tagLen, "noStartTag");
                    badParse();
                } else {
                    stackTop = tagStack.pop();
                    if (stackTop.tag !== tagString) {
                        reportIssue(start, tagLen, "misMatchTag");
                        reportIssue(stackTop.start, stackTop.tagLen, "misMatchTag");
                        badParse();
                    } else if ((stackTop.start + stackTop.tagLen) === start) {
                        reportIssue(start, tagLen, "emptyTag");
                        reportIssue(stackTop.start, stackTop.tagLen, "emptyTag");
                    }
                }
            } else {    // startTag
                if (tagStack.some(match)) { // check if any already in stack
                    reportIssue(start, tagLen, "nestedTag");
                    badParse();
                }
                if (/[,.;:!?]/.test(postChar)) {
                    reportIssue(end, 1, "puncAfterStart");
                }
                if (postChar === " ") {
                    reportIssue(end, 1, "spaceAfterStart");
                }
                if (postChar === "\n") {
                    reportIssue(start, tagLen, "nlAfterStart");
                }
                // letter or ,.;:
                if (XRegExp("\\pL|[,.;:]", "Ag").test(preChar)) {
                    reportIssue(start - 1, 1, "charBeforeStart");
                }
                tagStack.push({tag: tagString, start: start, tagLen: tagLen});
            }
        }
        // if there are any tags on the stack mark them as errors
        while (tagStack.length !== 0) {
            stackTop = tagStack.pop();
            reportIssue(stackTop.start, stackTop.tagLen, "noEndTag");
            badParse();
        }
    }

    // check for no upper case between small caps tags
    function checkSC() {
        var result;
        var re = /<sc>([^]*?)<\/sc>/g; // <sc> text
        var res1;
        while ((result = re.exec(txt)) !== null) {
            res1 = result[1];
            if (res1 === res1.toLowerCase()) {
                if(res1.charAt(0) !== "*") {
                    // definite issue
                    reportIssue(result.index, 4, "scNoCap", 1);
                } else {
                    // a lower case fragment, mark first character
                    // incase no tags shown
                    reportIssue(result.index + 4, 1, "scNoCap", 0);
                }
            }
        }
    }

    function checkBlankNumber() { // only 1, 2 or 4 blank lines should appear
        var result;
        var end;
        var re = /^\n{3}.|.\n{4}.|^\n{5,}.|.\n{6,}./g;

        while ((result = re.exec(txt)) !== null) {
            re.lastIndex -= 1; // in case only one char, include it in next search
            end = result.index + result[0].length;
            reportIssue(end - 1, 1, "blankLines124");
            badParse();
        }
    }

    function testBoldBlock() {
        // split txt into blocks surrounded by blank lines
        // find the number of blank lines preceding the block
        // at beginning of page assume in a paragraph
        let prevEnd = -1;
        // start is first non \n character
        let beginRegex = /./g;
        // end of block is \n\n or \n$ or $
        let endRegex = /\n\n|\n*$/g;
        // headBlock is true for a heading or sub-heading
        // 4 blank lines changes to heading mode
        // 2 blank lines changes to paragraph mode
        // 1 blank line stay in same mode
        // 0 blank lines only occurs at start of page; continuation paragraph
        let headBlock = false;

        for(;;) {
            let result = beginRegex.exec(txt);
            if(null === result) {
                return;
            }
            let blockStart = result.index;
            endRegex.lastIndex = result.index;
            result = endRegex.exec(txt);
            let blockEend = result.index;
            beginRegex.lastIndex = result.index;
            // preceding blank lines are # of characters (\n) between this block
            // and the preceding one -1
            let precedingBlanks = blockStart - prevEnd - 1;
            prevEnd = blockEend;

            // decide type of block from preceding blank lines
            switch(precedingBlanks) {
            case 4:
                // heading
                headBlock = true;
                break;
            case 2:
                // Section or paragraph
                headBlock = false;
                break;
            default:
                // headBlock state retained
                break;
            }
            if((txt.indexOf("<b>", blockStart) === blockStart) && (txt.indexOf("</b>", blockStart) === (blockEend - "</b>".length))) {
                // heading: issue, paragraph: possible issue
                if(headBlock) {
                    reportIssue(blockStart, 3, "noBold");
                } else {
                    // highlight after tags to avoid interleaved spans with style markup
                    let markPoint = blockStart + "<b>".length;
                    // if there is another start tag here advance past it.
                    while(txt.charAt(markPoint) == '<') {
                        markPoint = txt.indexOf('>', markPoint) + 1;
                    }
                    reportIssue(markPoint, 1, "boldPara");
                }
            }
        }
    }

    // check that footnote anchors and footnotes correspond
    // also check footnote id and [*] anchors
    // first make arrays of anchors and footnote ids, then check correspondence
    function checkFootnotes() {
        var anchorArray = [];
        var footnoteArray = [];
        // let footnote marker be an optionally tagged letter, or a number
        // Bad tag caught elsewhere
        let marker = `(?:<(?:${ILTags})>)?(?:[A-Za-z]|\\d+)(?:<.+>)?`;
        let footnoteIDRegex = new RegExp(`^(?:${marker})$`);
        // also check for *
        let anchorRegex = new RegExp(`\\[(\\*|${marker})\\]`, "g");

        function checkAnchor(anch) {
            function match(fNote) {
                return fNote.id === anch.id;
            }
            if (!footnoteArray.some(match)) {
                reportIssue(anch.index, anch.id.length + 2, "noFootnote");
            }
        }

        function checkNote(fNote) {
            var anchorIx = [];
            var markLen;

            function findId(anch) {
                if (anch.id === fNote.id) {
                    anchorIx.push(anch.index);
                }
            }

            function dupReport(index) {
                reportIssue(index, markLen, "multipleAnchors");
            }

            // make an array of anchor indexes for this footnote
            anchorArray.forEach(findId);
            var noteNum = anchorIx.length;
            if (0 === noteNum) {
                reportIssue(fNote.index, 9, "noAnchor");
            } else if (noteNum > 1) {
                markLen = fNote.id.length + 2;
                anchorIx.forEach(dupReport);
            }
        }

        // search for footnote anchors and put in an array
        let result;
        while ((result = anchorRegex.exec(txt)) !== null) {
            if (result[1] === "*") {    // found [*]
                reportIssue(result.index, 3, "starAnchor");
                continue;
            }
            anchorArray.push({index: result.index, id: result[1]});
        }
        // search for footnotes, get text to end of line
        let footnoteRegex = /\[Footnote(.*)/g;
        let noteLine, colonIndex, footnoteStartIndex;
        function match(fNote) {
            return fNote.id === noteLine;
        }
        while ((result = footnoteRegex.exec(txt)) !== null) {
            noteLine = result[1];
            // find text up to :
            colonIndex = noteLine.indexOf(":");
            footnoteStartIndex = result.index;
            if (-1 === colonIndex) {
                reportIssue(footnoteStartIndex, 9, "noColon");
                continue;
            }
            if (txt.charAt(footnoteStartIndex - 1) === "*") { // continuation
                if (colonIndex !== 0) {
                    reportIssue(footnoteStartIndex, 9, "colonNext");
                } else if (footnoteArray.length > 0) {
                    reportIssue(footnoteStartIndex, 9, "continueFirst");
                }
                continue;
            }
            if (!(/^ [^ ]/).test(noteLine)) {
                reportIssue(footnoteStartIndex, 9, "spaceNext");
                continue;
            }
            noteLine = noteLine.slice(1, colonIndex);    // the id
            if (!footnoteIDRegex.test(noteLine)) {
                reportIssue(footnoteStartIndex, 9, "footnoteId");
                continue;
            }
            if (footnoteArray.some(match)) {
                reportIssue(footnoteStartIndex, 9, "dupNote");
                continue;
            }
            footnoteArray.push({index: footnoteStartIndex, id: noteLine});
        }
        anchorArray.forEach(checkAnchor);
        footnoteArray.forEach(checkNote);
    }

    // check blank lines before and after footnote, sidenote, illustration
    // & <tb>. Do separately since different special cases for each
    // possible issue if there is an unmatched ] in the text
    // message includes "Footnote" etc. to clarify the problem
    function checkBlankLines() {
        // check for chars before tag or on previous line
        function chkBefore(start, len, checkBlank) {
            if (/./.test(txt.charAt(start - 1))) {
                reportIssue(start, len, "charBefore");
            } else if (checkBlank && (/./.test(txt.charAt(start - 2)))) {
                reportIssue(start, len, "blankBefore");
            }
        }

        // check no chars follow on same line and next line is blank
        function chkAfter(start, len, descriptor, type, checkBlank) {
            // true if find */ or \n or eot
            function endNWorBlank(pc) {
                if (txt.slice(pc, pc + 2) === "*/") {
                    return true;
                }
                return !(/./).test(txt.charAt(pc));
            }

            if(chkCharAfter(start, len, type, descriptor)) {
                return;
            }

            const end = findEnd(start + len);
            if (checkBlank && !endNWorBlank(end + 1)) {
                reportIssue(start, len, "blankAfter", type, descriptor);
            }
        }

        var result, start, len, end, end1;
        var re = /\*?\[(Footnote)/g;
        while ((result = re.exec(txt)) !== null) {
            start = result.index;
            len = result[0].length;
            end = start + len;
            chkBefore(start, len, true);

            end1 = findClose(txt, end);
            if (0 === end1) { // no ] found
                reportIssue(start, len, "noCloseBrack");
            } else {
                end = end1 + 1;
                len = 1;
                if (txt.charAt(end) === "*") { // allow * after Footnote
                    end += 1;
                    len += 1;
                }
                chkAfter(end1, len, result[1], 0, true);
            }
        }
        re = /\*?\[(Illustration)/g;
        while ((result = re.exec(txt)) !== null) {
            start = result.index;
            len = result[0].length;
            end = start + len;
            chkBefore(start, len, true);

            end1 = findClose(txt, end);
            if (0 === end1) { // no ] found
                reportIssue(start, len, "noCloseBrack");
            } else {
                end = end1 + 1;
                len = 1;
                chkAfter(end1, len, result[1], 0, true);
            }
        }
        re = /\*?\[(Sidenote)/g;
        while ((result = re.exec(txt)) !== null) {
            start = result.index;
            len = result[0].length;
            end = start + len;
            chkBefore(start, len, !config.suppress.sideNoteBlank);

            end1 = findClose(txt, end);
            if (0 === end1) { // no ] found
                reportIssue(start, len, "noCloseBrack");
            } else {
                end = end1 + 1;
                len = 1;
                chkAfter(end1, len, result[1], 0, !config.suppress.sideNoteBlank);
            }
        }
        re = /<tb>/g;
        while ((result = re.exec(txt)) !== null) {
            start = result.index;
            len = result[0].length;
            chkBefore(start, len, true);
            // always an issue to avoid conflict with marking the tag
            chkAfter(start, len, "&lt;tb&gt;", 1, true);
        }
    }

    // check that math delimiters \[ \], \( \) are matched
    // allow inline math inside display math (in text{})
    function checkMath() {
        var tagStack = [];  // holds start tag [ or (
        var stackTop;
        const mathRe = /\\\[|\\\]|\\\(|\\\)/g;
        let result;
        let tag;
        let start;
        while((result = mathRe.exec(txt)) !== null) {
            start = result.index;
            tag = result[0].charAt(1);
            if (tagStack.length === 0) {
                // no tags on stack
                if ((tag === '(') || (tag === '[')) {
                    tagStack.push({tag: tag, start: start});
                } else {
                    reportIssue(start, 2, "noStartTag");
                }
            } else {
                // there are tags in the stack
                stackTop = tagStack[tagStack.length - 1];
                if(stackTop.tag === '[') {
                    // ] or ( ok, [ or ) error
                    switch(tag) {
                    case ']':
                        tagStack.pop();
                        break;
                    case '(':
                        tagStack.push({tag: tag, start: start});
                        break;
                    case '[':
                        // report error for stack tag push the new one
                        tagStack.pop();
                        tagStack.push({tag: tag, start: start});
                        reportIssue(stackTop.start, 2, "noEndTag");
                        break;
                    case ')':
                        tagStack.pop();
                        reportIssue(stackTop.start, 2, "misMatchTag");
                        reportIssue(start, 2, "misMatchTag");
                        break;
                    }
                } else {
                    // stacktop is (, ) ok else error
                    switch(tag) {
                    case ')':
                        tagStack.pop();
                        break;
                    case '(':
                    case '[':
                        // report error for stack tag push the new one
                        tagStack.pop();
                        tagStack.push({tag: tag, start: start});
                        reportIssue(stackTop.start, 2, "noEndTag");
                        break;
                    case ']':
                        tagStack.pop();
                        reportIssue(stackTop.start, 2, "misMatchTag");
                        reportIssue(start, 2, "misMatchTag");
                        break;
                    }
                }
            }
        }
        // if there are any tags on the stack mark them as errors
        while (tagStack.length !== 0) {
            stackTop = tagStack.pop();
            reportIssue(stackTop.start, 2, "noEndTag");
        }
    }

    checkProoferNotes();
    if(parseOK) {
        txt = removeAllNotes(txt);
        parseInLine();
        // if inline parse fails then checkSC might not work
        if (parseOK) {
            checkSC();
        }
        checkBlankNumber();
        if (parseOK) {
        // only do this if inline parse succeeded and blank lines ok
            testBoldBlock();
        }
        parseOol();
        checkFootnotes();
        checkBlankLines();
        if(config.allowMathPreview) {
            checkMath();
        }
    }
    // we must avoid two issues giving overlapping markup
    // sort them first from beginning to end
    // sort them so that if two start in same place, then the more serious
    // placed first. Then 2nd will be discarded later.
    // Do this here so we can test the result.
    issArray.sort(function (a, b) {
        // if return value > 0, b is placed first
        var diff = a.start - b.start;
        if (diff === 0) {
            return b.type - a.type;
        } else {
            return diff;
        }
    });

    // end0 is end of previous issue to check if 2 issues overlap
    let end0 = 0;
    let condensedIssues = [];
    issArray.forEach(function(issue) {
        // don't mark 2 issues in one place
        if (issue.start >= end0) {
            condensedIssues.push(issue);
            end0 = issue.start + issue.len;
        }
    });

    return {
        issues: condensedIssues,
        text: txt,
        noteArray: notes,
    };
}; // end of analyse

/*
This function checks the text for formatting issues and adds the markup
for colouring and issue highlighting.
It can be used alone with a simple html interface for testing.
getMessage is a function to get a translated message.
txt is the text to analyse.
viewMode determines if the inline tags are to be shown or hidden
wrapMode whether to re-wrap the text.
styler is an object containing colour and font options.
*/
const makePreview = function (txt, viewMode, wrapMode, styler, getMessage) {
    const ILTags = getILTags(styler);
    const endSpan = "</span>";

    function htmlEncode(s) {
        return s.replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
    }

    // add style and optional colouring for marked-up text
    function showStyle() {
        const sc1 = "┌sc┐";
        const sc2 = "┌/sc┐";
        // a string of small capitals
        let smallCapRegex = new RegExp(sc1 + "([^]+?)" + sc2, 'g');

        function boxHtml(txt) {
            return txt.replace(/┌/g, "&lt;").replace(/┐/g, "&gt;")
                .replace(/▙/g, "&amp;");
        }

        function transformSC(match, p1) { // if all upper case transform to lower
            let scString = p1;
            // remove tags such as <i> within the string so that all
            // uppercase string is correctly identified, (only 1 char)
            scString = scString.replace(/┌\/?.┐/g, '');
            if (scString === scString.toUpperCase()) { // found no lower-case
                return sc1 + '<span class="tt">' + p1 + endSpan + sc2;
            } else {
                return match;
            }
        }

        function decideClass(colorClass, formatClass) {
            let classes = [];
            if (styler.color) {
                classes.push(`${colorClass}_color`);
            }
            if (viewMode !== "flat") {
                classes.push(formatClass);
            }
            return (classes.length > 0) ? ` class='${classes.join(" ")}'` : '';
        }

        function spanStyle(match, p1, p2) {
        // p1 is "/" or "", p2 is the tag id
            var tagMap = {
                "i": "_",
                "b": "=",
                "f": "~",
                "g": "$",
                "sc": "",
                "u": "%"
            };

            var tagMark = "";
            switch (viewMode) {
            case "show_tags":
                tagMark = boxHtml(match);
                break;
            case "flat":
                tagMark = tagMap[p2];
                break;
            default: // no_tags
                break;
            }
            if (p1 === '/') {   // end tag
                return tagMark + endSpan;
            }
            let styleClass = decideClass(p2, p2);
            return `<span${styleClass}>${tagMark}`;
        }

        // inline tags
        // the way html treats small cap text is different to the dp convention
        // so if sc-marked text is all upper-case transform to lower
        if (viewMode !== "flat") {
            txt = txt.replace(smallCapRegex, transformSC);
        }
        // find inline tags
        var reTag = new RegExp("┌(\\/?)(" + ILTags + ")┐", "g");
        txt = txt.replace(reTag, spanStyle);

        // out of line tags and <tb>
        if (!wrapMode && styler.color) {    // not re-wrap and colouring
            txt = txt.replace(/┌tb┐/g, function replacer(match) {
                return `<span class="etc_color">${boxHtml(match)}</span>`;
            });
            txt = txt.replace(/\/#/g, "<span class='blockquote_color'>$&");
            txt = txt.replace(/\/\*/g, "<span class='nowrap_color'>$&");
            txt = txt.replace(/#\/|\*\//g, "$&</span>");
        }

        // encode any unrecognised tags
        txt = boxHtml(txt);

        // show sub- and super-scripts
        function showSubSuper(text) {
            function ssReplace(regex, ssClass) {
                let styleClass = decideClass("etc", ssClass);
                let tagText = (viewMode === "no_tags") ? "$1" : "$&";
                text = text.replace(regex, `<span${styleClass}>${tagText}</span>`);
            }
            ssReplace(/_\{(.+?)\}/g, "sub");
            ssReplace(/\^\{(.+?)\}/g, "sup");
            // single char superscript -  any char except {
            // do not allow < as a single char superscript
            // incase it's a tag which would give overlapping markup
            ssReplace(/\^([^{<])/g, "sup");
            return text;
        }

        // do not process sub- and super-scripts inside math markup
        txt = processExMath(txt, showSubSuper, styler.allowMathPreview);
    }

    // Attempt to make an approximate representation of formatted text.
    // Remove proofers' notes.
    // Treat illustration, footnote, sidenote like ordinary text.
    // Re-wrap except for no-wrap markup.
    // First-line indent paragraphs except for continuation at start of page.
    // Extra indent for each block-quote markup.
    // Centre and embolden headings and sub-headings, except for a
    // sub-heading in block-quote or no-wrap. (subsequent sub-headings will
    // be centred).
    // Mark thought breaks by a horizontal line.

    function reWrap() {
        const lines = txt.split('\n');
        let lineIndex = 0;

        function getNextLine() {
            if(lineIndex >= lines.length) {
                return null;
            }
            return lines[lineIndex++];
        }

        const PARA = 0;
        const HEAD1 = 1;
        const HEAD2 = 2;
        const CONT = 3;

        let txtOut = "";
        let line;

        function processTB() {
            if ("&lt;tb&gt;" === line) {
                txtOut += '<div class="tb"></div>';
                return true;
            }
            return false;
        }

        // Count the number of consecutive blank lines, then at a non-blank line
        // set the blockType according to number of blanks and reset blank counter.
        // At start of each text block output <div> with appropriate style.
        // Output </div> at next blank line or end of text.

        let blanks = 0;
        let inBlock = false;
        let indent = 0;
        let blockType = CONT;
        let blockQuote = 0;
        const noWrapColor = styler.color ? ' nowrap_color' : '';

        function terminate() {
            if(inBlock) {
                txtOut += "</div>";
                inBlock = false;
            }
            blanks += 1;
        }

        for(;;) {
            line = getNextLine();
            if(null === line) {
                terminate();
                break;
            } else if("" === line) {
                terminate();
            } else {
                switch(blanks) {
                case 4:
                    blockType = HEAD1;
                    break;
                case 2:
                    blockType = PARA;
                    break;
                case 1:
                    if(blockType === CONT) {
                        blockType = PARA;
                    } else if(blockType === HEAD1) {
                        // sub heading next
                        blockType = HEAD2;
                    }
                    // else retain blockType
                    break;
                default:
                    // 0, do nothing
                    break;
                }
                blanks = 0;
                if ("/#" === line) {
                    blockQuote += 1;
                    indent += 1;
                } else if ("#/" === line) {
                    blockQuote -= 1;
                    indent -= 1;
                } else if("/*" === line) {
                    // no wrap
                    txtOut += `<div style='margin-left: ${indent}em;' class='no-wrap${noWrapColor}'>`;
                    for(;;) {
                        line = getNextLine();
                        if(processTB()) {
                            continue;
                        }
                        if (line === "*/") {
                            txtOut += "</div>";
                            break;
                        } else {
                            txtOut += line + "\n";
                        }
                    }
                } else if(processTB()) {
                    continue;
                } else {
                    // text line
                    const paraColor = (styler.color && blockQuote) ? ' blockquote_color' : '';
                    if(!inBlock) {
                        inBlock = true;
                        switch(blockType) {
                        case HEAD1:
                            txtOut += '<div class="head1">';
                            break;
                        case HEAD2:
                            if(blockQuote) {
                                // use paragraph style
                                txtOut += `<div style='margin-left: 1em;' class='para${paraColor}'>`;
                            } else {
                                txtOut += '<div class="head2">';
                            }
                            break;
                        case PARA:
                            txtOut += `<div style='margin-left: ${indent}em;' class='para${paraColor}'>`;
                            break;
                        case CONT:
                            txtOut += `<div style='margin-left: ${indent}em;' class='cont-para${paraColor}'>`;
                            break;
                        }
                    }
                    txtOut += line + "\n";
                }
            }
        }
        txt = txtOut;
    }

    // Show style is done after merging text and proofers notes.
    // To distinguish the < and > characters in text from those in the notes
    // (so we don't style the notes)
    // html encode the notes but in text encode < > to box characters.
    // We must also encode ampersand to avoid accidental entities like &copy; and
    // must do it before introducing html entities for < and > (&lt; &gt;),
    // but we can't html encode it now because if & occurs in all upper case
    // small caps markup which we have to convert to lower case because of
    // dp convention it would make it appear as not all upper case.
    // So encode & to a box character ▙ and convert to &amp; later.
    function boxEncode(txt) {
        return txt.replace(/</g, "┌").replace(/>/g, "┐")
            .replace(/&/g, "▙");
    }

    let analysis = analyse(txt, styler);
    let issArray = analysis.issues;
    let issues = 0;
    let possIss = 0;
    issArray.forEach(function(issue) {
        if(issue.type === 1) {
            issues += 1;
        } else {
            possIss += 1;
        }
    });

    // ok true if no errors which would cause showstyle() or reWrap() to fail
    let ok = (issues === 0);

    let issueStarts = [];
    let issueEnds = [];
    issArray.forEach(function(issue) {
        let issueStyle = (issue.type === 0) ? "hlt_color" : "err_color";
        let message = getMessage(issue.code).replace("%s", issue.subText);
        issueStarts.push({start: issue.start, text: `<span class='${issueStyle}' title='${message}'>`});
        issueEnds.push({start: issue.start + issue.len, text: endSpan});
    });

    var tArray = analysis.text.split("");

    let noteArray;
    if(ok && wrapMode) {
        // leave out proofers' notes
        noteArray = [];
    } else {
        noteArray = analysis.noteArray;
        noteArray.forEach(function(note) {
            note.text = htmlEncode(note.text);
        });
    }

    if(!ok) {
        tArray = tArray.map(htmlEncode);
    } else {
        tArray = tArray.map(boxEncode);
    }

    // merge issue arrays with notes so that if both start at same
    // index the issueStart appears after the note but the issueEnd
    // appears before the note.
    // there cannot be > one issue at the same index (if there were, one
    // will have been discarded) but there can be more than one note, so
    // ensure order of notes is unchanged
    let allInserts = issueEnds.concat(noteArray).concat(issueStarts);
    allInserts.sort(function(a, b) {
        // if starts are same return 0, order unchanged
        return a.start - b.start;
    });
    // reverse the array so splice last elements first
    allInserts.reverse();
    // splice issue marking and notes into text
    allInserts.forEach(function (insert) {
        tArray.splice(insert.start, 0, insert.text);
    });

    // join it back into a string
    txt = tArray.join("");

    if (ok) {
        showStyle();
        if (wrapMode) {
            reWrap();
        }
    }

    return {
        ok: ok,
        txtout: txt,
        issues: issues,
        possIss: possIss
    };
};

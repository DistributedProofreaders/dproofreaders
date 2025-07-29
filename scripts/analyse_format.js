import translate from "./gettext.js";

// find index of next unmatched ] return 0 if none found
const re = /\[|\]/g; // [ or ]
export function findClose(txt, index) {
    let result;
    let nestLevel = 0;
    re.lastIndex = index;
    while ((result = re.exec(txt)) !== null) {
        if ("[" === result[0]) {
            nestLevel += 1;
        } else {
            // must be ]
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
    txt = txt.replace(/ *$/gm, "");
    txt = txt.replace(/\s*$/, "");
    return txt;
}

export const getILTags = function (configuration) {
    let ILTags = "[ibfg]|sc";
    if (configuration.allowUnderline) {
        ILTags += "|u";
    }
    return ILTags;
};

export function analyse(txt, config) {
    config.suppress ?? (config.suppress = {});
    const ILTags = getILTags(config);

    // the default issue types, can be over-ridden
    // 1 means a definite issue, 0 a possible issue
    let issueType = {
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

    const issArray = []; // stores issues for markup-insertion later

    // this records issues which could prevent checkSmallCaps and testBoldBlock from
    // working. Not all definite issues mean bad parse
    let parseOK = true;
    function badParse() {
        parseOK = false;
    }

    // make an entry in issArray. start, len: position in text to mark
    // code: issue, optional type overrides issueType,
    function reportIssue(start, len, code, text, type = null) {
        if (!config.suppress[code]) {
            if (type == null) {
                type = issueType[code];
            }
            issArray.push({ start: start, len: len, code: code, text: text, type: type });
        }
    }

    const reNoteStart = /\[\*\*/g;

    function checkProoferNotes() {
        // look for [** then look for ] skipping matched [ ]
        let result, closeIndex;
        while (null !== (result = reNoteStart.exec(txt))) {
            closeIndex = findClose(txt, reNoteStart.lastIndex);
            if (0 === closeIndex) {
                // no ] found
                reportIssue(result.index, 3, "noCloseBrack", translate.gettext("No matching closing bracket"), 1);
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
            if ((noteStartIndex === 0 || txt.charAt(noteStartIndex - 1) === "\n") && (noteEndIndex === maxIndex || txt.charAt(noteEndIndex + 1) === "\n")) {
                // let next copy begin after the ending \n
                beginIndex = noteEndIndex + 2;
            } else {
                // let next copy begin after the note
                beginIndex = noteEndIndex + 1;
            }
            notes.push({ start: outNoteStart, text: txt.slice(noteStartIndex, beginIndex) });
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
            reportIssue(start, len, "charAfter", translate.gettext("No characters should follow %s").replace("%s", descriptor), type);
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
        var tagStack = []; // holds start tag /* or /# and index
        var start;
        var tagString;
        var stackTop;
        var result;
        var oolre = /\/\*|\/#|\*\/|#\//g; // any out-of-line tag
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
            if (chkCharAfter(start, len, 1, descriptor)) {
                return;
            }

            if (/./.test(txt.charAt(start - 1))) {
                reportIssue(start, len, "charBefore", translate.gettext("No characters should precede this"));
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
            if (tagString.charAt(0) === "/" && start > 1 && txt.charAt(start - 2) !== "\n") {
                prevLin = findPrevLine(start);
                if (!("/#" === prevLin || "/*" === prevLin)) {
                    reportIssue(start, 2, "OolPrev", translate.gettext("Out-of-line start tag should not be preceded by normal text"));
                }
            }
            // for a closing tag check following line is blank
            // or a closing block quote or ] (ending a footnote).
            // allow also closing no-wrap to avoid misleading error message
            if (tagString.charAt(1) === "/") {
                if (/[^#*\n\]]/.test(txt.charAt(findEnd(start + 2) + 1))) {
                    reportIssue(start, 2, "OolNext", translate.gettext("Out-of-line end tag should not be followed by normal text"));
                }
            }

            if (tagStack.length === 0) {
                if ("/" === tagString.charAt(0)) {
                    // start tag
                    tagStack.push({ tag: tagString, start: start });
                } else {
                    reportIssue(start, 2, "noStartTag", translate.gettext("No start tag for this end tag"));
                }
            } else {
                // there are tags in the stack
                stackTop = tagStack[tagStack.length - 1];
                if (stackTop.tag.charAt(1) === "*") {
                    // open NW;
                    switch (tagString) {
                        case "*/": // close NW ok
                            tagStack.pop();
                            break;
                        case "#/": // close BQ
                            tagStack.pop();
                            reportIssue(start, 2, "misMatchTag", translate.gettext("End tag does not match start tag"));
                            reportIssue(stackTop.start, 2, "misMatchTag", translate.gettext("End tag does not match start tag"));
                            break;
                        case "/*": // open NW
                            reportIssue(start, 2, "NWinNW", translate.gettext("No-wrap inside no-wrap"));
                            tagStack.push({ tag: tagString, start: start });
                            break;
                        default: // open BQ
                            reportIssue(start, 2, "BQinNW", translate.gettext("Block quote inside no-wrap"));
                            tagStack.push({ tag: tagString, start: start });
                            break;
                    }
                } else {
                    // top of stack is /#
                    switch (tagString) {
                        case "#/": // close BQ
                            tagStack.pop();
                            break;
                        case "*/": // close NW
                            tagStack.pop();
                            reportIssue(start, 2, "misMatchTag", translate.gettext("End tag does not match start tag"));
                            reportIssue(stackTop.start, 2, "misMatchTag", translate.gettext("End tag does not match start tag"));
                            break;
                        default: // open either
                            tagStack.push({ tag: tagString, start: start });
                            break;
                    }
                }
            }
        }
        // if there are any tags on the stack mark them as errors
        while (tagStack.length !== 0) {
            stackTop = tagStack.pop();
            reportIssue(stackTop.start, 2, "noEndTag", translate.gettext("No end tag for this start tag"));
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
                    reportIssue(stackTop.start, stackTop.tagLen, "noEndTagInPara", translate.gettext("No corresponding end tag in paragraph"));
                    badParse();
                }
                continue;
            }
            const possibleTag = possTagResult[0];
            if (possibleTag.match(/^<tb>/)) {
                continue;
            }
            const goodTagResult = possibleTag.match(reGoodTag);
            start = possTagResult.index;
            if (!goodTagResult) {
                reportIssue(start, 1, "unRecTag", translate.gettext("Unrecognized tag"));
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
            if (goodTagResult[1] === "/") {
                // end tag
                // check for , ; or : before end tag except at end of text
                if (/[,;:]/.test(preChar) && txt.length !== end) {
                    reportIssue(start - 1, 1, "puncBEnd", translate.gettext(", ; or : before end tag"));
                }
                if (preChar === " ") {
                    reportIssue(start - 1, 1, "spaceBeforeEnd", translate.gettext("Space before end tag"));
                }
                if (preChar === "\n") {
                    reportIssue(start, tagLen, "nlBeforeEnd", translate.gettext("Newline before end tag"));
                }
                // letter or number after end tag
                if (/\p{L}|\p{N}/gu.test(postChar)) {
                    reportIssue(end, 1, "charAfterEnd", translate.gettext("Character after inline end tag"));
                }
                if (tagStack.length === 0) {
                    // missing start tag
                    reportIssue(start, tagLen, "noStartTag", translate.gettext("No start tag for this end tag"));
                    badParse();
                } else {
                    stackTop = tagStack.pop();
                    if (stackTop.tag !== tagString) {
                        reportIssue(start, tagLen, "misMatchTag", translate.gettext("End tag does not match start tag"));
                        reportIssue(stackTop.start, stackTop.tagLen, "misMatchTag", translate.gettext("End tag does not match start tag"));
                        badParse();
                    } else if (stackTop.start + stackTop.tagLen === start) {
                        reportIssue(start, tagLen, "emptyTag", translate.gettext("Empty tag"));
                        reportIssue(stackTop.start, stackTop.tagLen, "emptyTag", translate.gettext("Empty tag"));
                    }
                }
            } else {
                // startTag
                if (tagStack.some(match)) {
                    // check if any already in stack
                    reportIssue(start, tagLen, "nestedTag", translate.gettext("Tag nested within same tag"));
                    badParse();
                }
                if (/[,.;:!?]/.test(postChar)) {
                    reportIssue(end, 1, "puncAfterStart", translate.gettext("Punctuation after start tag"));
                }
                if (postChar === " ") {
                    reportIssue(end, 1, "spaceAfterStart", translate.gettext("Space after start tag"));
                }
                if (postChar === "\n") {
                    reportIssue(start, tagLen, "nlAfterStart", translate.gettext("Newline after start tag"));
                }
                // letter or ,.;:
                if (/\p{L}|[,.;:]/gu.test(preChar)) {
                    reportIssue(start - 1, 1, "charBeforeStart", translate.gettext("Character or punctuation before inline start tag"));
                }
                tagStack.push({ tag: tagString, start: start, tagLen: tagLen });
            }
        }
        // if there are any tags on the stack mark them as errors
        while (tagStack.length !== 0) {
            stackTop = tagStack.pop();
            reportIssue(stackTop.start, stackTop.tagLen, "noEndTag", translate.gettext("No end tag for this start tag"));
            badParse();
        }
    }

    // check for no upper case between small caps tags
    function checkSmallCaps() {
        let result;
        const re = /<sc>([^]*?)<\/sc>/g;
        while ((result = re.exec(txt)) !== null) {
            const res1 = result[1];
            if (res1 === res1.toLowerCase()) {
                if (res1.charAt(0) !== "*") {
                    // could be fragment of a word at beginning
                    // otherwise definite issue
                    reportIssue(result.index, 4, "scNoCap", translate.gettext("Small caps must contain at least one upper case character"), 1);
                } else {
                    // a lower case fragment, mark first character
                    // incase no tags shown
                    reportIssue(result.index + 4, 1, "scNoCap", translate.gettext("Small caps must contain at least one upper case character"), 0);
                }
            }
        }
    }

    function checkBlankNumber() {
        // only 1, 2 or 4 blank lines should appear
        var result;
        var end;
        var re = /^\n{3}.|.\n{4}.|^\n{5,}.|.\n{6,}./g;

        while ((result = re.exec(txt)) !== null) {
            re.lastIndex -= 1; // in case only one char, include it in next search
            end = result.index + result[0].length;
            reportIssue(end - 1, 1, "blankLines124", translate.gettext("Only 1, 2 or 4 blank lines should be used"));
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

        for (;;) {
            let result = beginRegex.exec(txt);
            if (null === result) {
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
            switch (precedingBlanks) {
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
            if (txt.indexOf("<b>", blockStart) === blockStart && txt.indexOf("</b>", blockStart) === blockEend - "</b>".length) {
                // heading: issue, paragraph: possible issue
                if (headBlock) {
                    reportIssue(blockStart, 3, "noBold", translate.gettext("Heading should not be entirely bold"));
                } else {
                    // highlight after tags to avoid interleaved spans with style markup
                    let markPoint = blockStart + "<b>".length;
                    // if there is another start tag here advance past it.
                    while (txt.charAt(markPoint) == "<") {
                        markPoint = txt.indexOf(">", markPoint) + 1;
                    }
                    reportIssue(markPoint, 1, "boldPara", translate.gettext("Entirely bold paragraph or section heading"));
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
                reportIssue(anch.index, anch.id.length + 2, "noFootnote", translate.gettext("No corresponding footnote on this page"));
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
                reportIssue(index, markLen, "multipleAnchors", translate.gettext("Multiple anchors for same footnote"));
            }

            // make an array of anchor indexes for this footnote
            anchorArray.forEach(findId);
            var noteNum = anchorIx.length;
            if (0 === noteNum) {
                reportIssue(fNote.index, 9, "noAnchor", translate.gettext("No anchor for this footnote"));
            } else if (noteNum > 1) {
                markLen = fNote.id.length + 2;
                anchorIx.forEach(dupReport);
            }
        }

        // search for footnote anchors and put in an array
        let result;
        while ((result = anchorRegex.exec(txt)) !== null) {
            if (result[1] === "*") {
                // found [*]
                reportIssue(result.index, 3, "starAnchor", translate.gettext("Footnote anchor should be an upper-case letter"));
                continue;
            }
            anchorArray.push({ index: result.index, id: result[1] });
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
                reportIssue(footnoteStartIndex, 9, "noColon", translate.gettext("Footnote must have a colon"));
                continue;
            }
            if (txt.charAt(footnoteStartIndex - 1) === "*") {
                // continuation
                if (colonIndex !== 0) {
                    reportIssue(footnoteStartIndex, 9, "colonNext", translate.gettext("The colon should immediately follow *[Footnote"));
                } else if (footnoteArray.length > 0) {
                    reportIssue(footnoteStartIndex, 9, "continueFirst", translate.gettext("Continuation footnote should precede others"));
                }
                continue;
            }
            if (!/^ [^ ]/.test(noteLine)) {
                reportIssue(footnoteStartIndex, 9, "spaceNext", translate.gettext("Footnote should be followed by one space and identifier"));
                continue;
            }
            noteLine = noteLine.slice(1, colonIndex); // the id
            if (!footnoteIDRegex.test(noteLine)) {
                reportIssue(footnoteStartIndex, 9, "footnoteId", translate.gettext("Footnote identifier should be a letter or number"));
                continue;
            }
            if (footnoteArray.some(match)) {
                reportIssue(footnoteStartIndex, 9, "dupNote", translate.gettext("Duplicate footnote identifier"));
                continue;
            }
            footnoteArray.push({ index: footnoteStartIndex, id: noteLine });
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
                reportIssue(start, len, "charBefore", translate.gettext("No characters should precede this"));
            } else if (checkBlank && /./.test(txt.charAt(start - 2))) {
                reportIssue(start, len, "blankBefore", translate.gettext("A blank line should precede this"));
            }
        }

        // check no chars follow on same line and next line is blank
        function chkAfter(start, len, descriptor, type, checkBlank) {
            // true if find */ or \n or eot
            function endNWorBlank(pc) {
                if (txt.slice(pc, pc + 2) === "*/") {
                    return true;
                }
                return !/./.test(txt.charAt(pc));
            }

            if (chkCharAfter(start, len, type, descriptor)) {
                return;
            }

            const end = findEnd(start + len);
            if (checkBlank && !endNWorBlank(end + 1)) {
                reportIssue(start, len, "blankAfter", translate.gettext("A blank line should follow %s").replace("%s", descriptor), type);
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
            if (0 === end1) {
                // no ] found
                reportIssue(start, len, "noCloseBrack", translate.gettext("No matching closing bracket"));
            } else {
                end = end1 + 1;
                len = 1;
                if (txt.charAt(end) === "*") {
                    // allow * after Footnote
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
            if (0 === end1) {
                // no ] found
                reportIssue(start, len, "noCloseBrack", translate.gettext("No matching closing bracket"));
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
            if (0 === end1) {
                // no ] found
                reportIssue(start, len, "noCloseBrack", translate.gettext("No matching closing bracket"));
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
    function checkMath() {
        const mathRe = /\\\[|\\\]|\\\(|\\\)/g;
        let openTag = null;
        let result;
        let tag;
        let startIndex;
        let tagIndex;
        while ((result = mathRe.exec(txt)) !== null) {
            tagIndex = result.index;
            tag = result[0].charAt(1);
            if (!openTag) {
                if (tag === "(" || tag === "[") {
                    openTag = tag;
                    startIndex = tagIndex;
                } else {
                    // found an end tag
                    reportIssue(tagIndex, 2, "noStartTag", translate.gettext("No start tag for this end tag"));
                }
            } else {
                // there is an open tag
                if (tag === "(" || tag === "[") {
                    reportIssue(startIndex, 2, "noEndTag", translate.gettext("No end tag for this start tag"));
                    openTag = tag;
                    startIndex = tagIndex;
                } else if ((openTag === "(" && tag === "]") || (openTag === "[" && tag === ")")) {
                    reportIssue(tagIndex, 2, "misMatchTag", translate.gettext("End tag does not match start tag"));
                    reportIssue(startIndex, 2, "misMatchTag", translate.gettext("End tag does not match start tag"));
                } else {
                    // ok
                    openTag = null;
                }
            }
        }
        if (openTag) {
            reportIssue(startIndex, 2, "noEndTag", translate.gettext("No end tag for this start tag"));
        }
    }

    checkProoferNotes();
    if (parseOK) {
        txt = removeAllNotes(txt);
        parseInLine();
        // if inline parse fails then checkSmallCaps might not work
        if (parseOK) {
            checkSmallCaps();
        }
        checkBlankNumber();
        if (parseOK) {
            // only do this if inline parse succeeded and blank lines ok
            testBoldBlock();
        }
        parseOol();
        checkFootnotes();
        checkBlankLines();
        if (config.allowMathPreview) {
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
    issArray.forEach(function (issue) {
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
}

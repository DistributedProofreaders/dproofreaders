/* eslint-disable camelcase */
/* exported makePreview analyse processExMath */
/* global $ previewMessages XRegExp */

var makePreview;
var analyse;
var getILTags;

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

$(function () {
    function removeComments(textLine) {
        return textLine.replace(/\[\*\*[^\]]*\]/g, '');
    }

    // true if txtLine contains anything except only comments or spaces
    function nonComment(textLine) {
        return (/\S/.test(removeComments(textLine)));
    }

    analyse = function (txt, config) {
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
            tabChar: 1,
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
            multipleAnchors: 0
        };

        var issArray = []; // stores issues for markup-insertion later

        // this records issues which could prevent checkSC and boldHeading from
        // working. Not all definite issues mean bad parse
        let parseOK = true;
        function badParse() {
            parseOK = false;
        }

        // make an entry in issArray. start, len: position in text to mark
        // code: issue, optional type overrides issueType,
        // subText is text to substitute in some messages
        function reportIssue(start, len, code, type, subText) {
            // if default params allowed: type = null, subText = ""
            // ie doesn't support default parameters so if not given
            // type and subText are undefined: (undefined == null) is true
            if (!(config.suppress[code])) {
                if(type == null) {
                    type = issueType[code];
                }
                if(subText == null) {
                    subText = "";
                }
                issArray.push({start: start, len: len, code: code, type: type, subText: subText});
            }
        }

        // find end of line (or eot) following ix
        function findEnd(ix) {
            var re = /\n|$/g;
            var result;
            re.lastIndex = ix;
            result = re.exec(txt);
            return result.index;
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
            function chkAlone(start, len, str1) {
                var ix = start + len;
                if (nonComment(txt.slice(ix, findEnd(ix)))) {
                    reportIssue(start, len, "charAfter", 1, str1);
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
                // or an opening block quote tag possibly with a comment
                // allow also an opening no-wrap to avoid giving a misleading message
                // that it is "normal text". The error will be caught elsewhere.
                if ((tagString.charAt(0) === "/") && (start > 1) && (txt.charAt(start - 2) !== "\n")) {
                    prevLin = removeComments(findPrevLine(start));
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
            var result;
            var res1;
            var stackTop;
            var preChar;
            var postChar;

            // find index in stack of ntag, return -1 if none found
            function match(tagData) {
                return tagData.tag === tagString;
            }

            // regex to match valid inline tags or a blank line or [**
            var re = new RegExp("\\[\\*\\*|<(\\/?)(" + ILTags + ")>|\\n\\n", "g");
            var reCloseBrack = /\]|$/gm;  // ] or eol or eot

            while ((result = re.exec(txt)) !== null) {
                if (result[0] === "[**") { // advance to end of comment or eol
                    reCloseBrack.lastIndex = re.lastIndex;
                    res1 = reCloseBrack.exec(txt);  // can't be null if has $
                    if (res1[0] !== "]") {
                    // user note missing ], make an issue to avoid parsing probs
                    // if there are tags in the note
                        reportIssue(result.index, 3, "noCloseBrack", 1);
                        badParse();
                    }
                    re.lastIndex = reCloseBrack.lastIndex;
                    continue;
                }

                if (result[0] === "\n\n") {
                    while (tagStack.length !== 0) {
                        stackTop = tagStack.pop();
                        reportIssue(stackTop.start, stackTop.tagLen, "noEndTagInPara");
                        badParse();
                    }
                    continue;
                }
                start = result.index;
                tagLen = result[0].length;
                end = start + tagLen;
                tagString = result[2];
                preChar = txt.charAt(start - 1);
                postChar = txt.charAt(end);
                if (result[1] === '/') {    // end tag
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
                    if (XRegExp("\\pL|\\pN", "Ag").test(postChar)) { // char after end tag
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

        // check for an unrecognised tag
        function unRecog() {
            let reMaybeTag = /<(\/?)(\w{1,3})>/g;
            let reGoodTag = new RegExp("^(?:" + ILTags + ")$");
            let result;
            // find all possible tags and check if valid
            while((result = reMaybeTag.exec(txt)) !== null) {
                let tagString = result[2];
                let tagLen = result[0].length;

                if(tagString === "tb") {
                    if(result[1]) {
                        // found </tb>
                        reportIssue(result.index, tagLen, "unRecTag");
                    }
                    // ignore thought break
                    continue;
                }

                if(!reGoodTag.test(tagString)) {
                    reportIssue(result.index, tagLen, "unRecTag");
                    continue;
                }
            }
        }

        // check for no upper case between small caps tags, ignore inside note
        function checkSC() {
            var reCloseBrack = /\]/g;
            var result;
            var re = /\[\*\*|<sc>([^]*?)<\/sc>/g; // [** or <sc> text
            var res1;
            while ((result = re.exec(txt)) !== null) {
                if (result[0] === "[**") {
                // advance to end of comment, caught no ] earlier
                    reCloseBrack.lastIndex = re.lastIndex;
                    reCloseBrack.exec(txt);
                    re.lastIndex = reCloseBrack.lastIndex;
                    continue;
                }
                res1 = result[1];
                if (res1 === res1.toLowerCase()) { //no upper case found - definite
                    reportIssue(result.index, 4, "scNoCap", 1);
                    continue;
                }
                res1 = removeComments(res1);
                if (res1 === res1.toLowerCase()) { //upper case was in a note - poss
                // mark only a text char incase no tags shown
                    reportIssue(result.index + 4, 1, "scNoCap", 0);
                }
            }
        }

        // find tab characters
        function checkTab() {
            var re = /\t/g;
            var result;
            while ((result = re.exec(txt)) !== null) {
                reportIssue(result.index, 1, "tabChar");
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

        // there should not be an entire bold single line after 2 or 4 blank lines
        function boldHeading() {
            var headLine;
            var re = /((?:^|\n)\n\n)(.+)\n\n/g; // the whole line
            // match a tag or any non-space char
            var re1 = new RegExp("<\\/?(?:" + ILTags + ")>|\\S", "g");
            var boldEnd = /<\/b>/g;

            function boldLine() {   // false if any non-bold char found
                var result1;
                re1.lastIndex = 0;  // else will be left from previous use
                while ((result1 = re1.exec(headLine)) !== null) {
                    if (result1[0].length === 1) {   // a non-bold char
                        return false;
                    }
                    if (result1[0] === "<b>") { // advance to </b>
                        boldEnd.lastIndex = re1.lastIndex;
                        if (null === boldEnd.exec(headLine)) { // shouldn't happen
                            return false;
                        }
                        re1.lastIndex = boldEnd.lastIndex;
                    }
                // must be another tag - continue
                }
                return true;
            }

            var result;
            var start;
            // first find the heading lines
            while ((result = re.exec(txt)) !== null) {
                headLine = result[2];
                re.lastIndex -= 2;  // so can find another straight after
                if (boldLine()) {
                    start = result.index + result[1].length;
                    reportIssue(start, 3, "noBold");
                }
            }
        }

        // check that footnote anchors and footnotes correspond
        // also check footnote id and [*] anchors
        // first make arrays of anchors and footnote ids, then check correspondence
        function checkFootnotes() {
            var anchorArray = [];
            var footnoteArray = [];

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
            // match an upper case letter or digits or *
            var result;
            var re = /\[(\*|[A-Za-z]|\d+)\]/g;
            while ((result = re.exec(txt)) !== null) {
                if (result[1] === "*") {    // found [*]
                    reportIssue(result.index, 3, "starAnchor");
                    continue;
                }
                anchorArray.push({index: result.index, id: result[1]});
            }
            // search for footnotes, get text to end of line
            re = /\[Footnote(.*)/g;
            var noteLine, i, rix;
            function match(fNote) {
                return fNote.id === noteLine;
            }
            while ((result = re.exec(txt)) !== null) {
                noteLine = result[1];
                // find text up to :
                i = noteLine.indexOf(":");
                rix = result.index;
                if (-1 === i) {
                    reportIssue(rix, 9, "noColon");
                    continue;
                }
                if (txt.charAt(rix - 1) === "*") { // continuation
                    if (i !== 0) {
                        reportIssue(rix, 9, "colonNext");
                    } else if (footnoteArray.length > 0) {
                        reportIssue(rix, 9, "continueFirst");
                    }
                    continue;
                }
                if (!(/^ [^ ]/).test(noteLine)) {
                    reportIssue(rix, 9, "spaceNext");
                    continue;
                }
                noteLine = noteLine.slice(1, i);    // the id
                if (!(/^[A-Za-z]$|^\d+$/).test(noteLine)) {
                    reportIssue(rix, 9, "footnoteId");
                    continue;
                }
                if (footnoteArray.some(match)) {
                    reportIssue(rix, 9, "dupNote");
                    continue;
                }
                footnoteArray.push({index: rix, id: noteLine});
            }
            anchorArray.forEach(checkAnchor);
            footnoteArray.forEach(checkNote);
        }

        // check blank lines before and after footnote, sidenote, illustration
        // & <tb>. Do separately since different special cases for each
        // possible issue if there is an unmatched ] in the text
        // message includes "Footnote" etc. to clarify the problem
        function checkBlankLines() {
        // find index of next unmatched ] return 0 if none found
            function findClose(index) {
                var result;
                var nestLevel = 0;
                var re = /\[|\]/g;  // [ or ]
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

            // check for chars before tag or on previous line
            function chkBefore(start, len, checkBlank) {
                if (/./.test(txt.charAt(start - 1))) {
                    reportIssue(start, len, "charBefore");
                } else if (checkBlank && (/./.test(txt.charAt(start - 2)))) {
                    reportIssue(start, len, "blankBefore");
                }
            }

            // check no non-comment chars follow on same line and next line is blank
            function chkAfter(start, len, str1, type, checkBlank) {
                // true if find */ or \n or eot
                function endNWorBlank(pc) {
                    if (txt.slice(pc, pc + 2) === "*/") {
                        return true;
                    }
                    return !(/./).test(txt.charAt(pc));
                }

                var ix = start + len;
                var end = findEnd(ix);
                if (nonComment(txt.slice(ix, end))) {
                    reportIssue(start, len, "charAfter", type, str1);
                    return;
                }
                if (checkBlank && !endNWorBlank(end + 1)) {
                    reportIssue(start, len, "blankAfter", type, str1);
                }
            }

            var result, start, len, end, end1;
            var re = /\*?\[(Footnote)/g;
            while ((result = re.exec(txt)) !== null) {
                start = result.index;
                len = result[0].length;
                end = start + len;
                chkBefore(start, len, true);

                end1 = findClose(end);
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

                end1 = findClose(end);
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

                end1 = findClose(end);
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

        // check that math delimiters \[ \], \( \) are correctly matched
        function checkMath() {
            let mathRe = /\\\[|\\\]|\\\(|\\\)/g;
            let result;
            let tag, openTag = false;
            let openStart = 0;
            while((result = mathRe.exec(txt)) !== null) {
                tag = result[0].charAt(1);
                // if no open tag and ( or [ set open else error
                if (!openTag) {
                    if ((tag === '(') || (tag === '[')) {
                        openTag = tag;
                        openStart = result.index;
                    } else {
                        reportIssue(result.index, 2, "noStartTag");
                    }
                } else {
                    if (((openTag === '(') && (tag === ')')) || ((openTag === '[') && (tag === ']'))) {
                        // correctly matched
                        openTag = false;
                    } else if (((openTag === '(') && (tag === ']')) || ((openTag === '[') && (tag === ')'))) {
                        // mismatched
                        openTag = false;
                        reportIssue(openStart, 2, "misMatchTag");
                        reportIssue(result.index, 2, "misMatchTag");
                    } else {
                        // a start tag of either sort follows a start tag
                        reportIssue(openStart, 2, "noEndTag");
                        openTag = tag;
                        openStart = result.index;
                    }
                }
            }
            // if there is a tag open mark it as an error
            if (openTag) {
                reportIssue(openStart, 2, "noEndTag");
            }
        }

        parseInLine();
        // if inline parse fails then checkSC might not work
        if (parseOK) {
            checkSC();
        }
        checkBlankNumber();
        if (parseOK) {
        // only do this is inline parse succeeded and blank lines ok
            boldHeading();
        }
        parseOol();
        checkFootnotes();
        unRecog();
        checkTab();
        checkBlankLines();
        if(config.allowMathPreview) {
            checkMath();
        }

        // we must avoid two issues giving overlapping markup
        // sort them first and mark from end towards beginning
        // sort them so that if two start in same place, then the more serious is
        // marked first. Do this here so we can test the result.
        issArray.sort(function (a, b) {
            var diff = b.start - a.start;   // last first
            if (diff === 0) {
                return b.type - a.type;
            } else {
                return diff;
            }
        });

        return issArray;
    };

    getILTags = function(configuration) {
        let ILTags = "[ibfg]|sc";
        if (configuration.allowUnderline) {
            ILTags += "|u";
        }
        return ILTags;
    };

    /*
    This function checks the text for formatting issues and adds the markup
    for colouring and issue highlighting.
    It can be used alone with a simple html interface for testing.
    previewMessages are translated strings in header args.
    previewControl.adjustMargin() is defined in previewControl.js
    txt is the text to analyse.
    viewMode determines if the inline tags are to be shown or hidden
    wrapMode whether to re-wrap the text.
    styler is an object containing colour and font options.
    */
    makePreview = function (txt, viewMode, wrapMode, styler) {
        const ILTags = getILTags(styler);
        const endSpan = "</span>";

        function makeColourStyle(s) {
            var style = styler[s];
            var have_style = false;
            var str = "";
            // style issues always even if coloring turned off
            if (!styler.color && (s !== "err") && (s !== "hlt")) {
                return str;
            }
            if (style.fg !== "") {
                have_style = true;
                str = 'color:' + style.fg + ';';
            }
            if (style.bg !== "") {
                have_style = true;
                str += ('background-color:' + style.bg + ';');
            }
            if (have_style) {
                str = ' style="' + str + '"';
            }
            return str;
        }

        function makeErrStr(st1) {
            return '<span class="err" onmouseenter="previewControl.adjustMargin(this)"' + makeColourStyle(st1) + '><span>';
        }

        function addMarkUp(issArray) {
        // start0 is start of previous issue to check if 2 issues overlap
        // initially a large number so it works 1st time
            var start0 = 100000;
            var end;
            var errorString;
            // split up the string into an array of characters
            var tArray = txt.split("");

            function htmlEncodeChar(s, i) {
                if (s === "&") {
                    tArray[i] = "&amp;";
                } else if (s === "<") {
                    tArray[i] = "&lt;";
                } else if (s === ">") {
                    tArray[i] = "&gt;";
                }
            }

            function markIss(iss) {
                if (iss.type === 0) {
                    errorString = makeErrStr("hlt");
                } else {
                    errorString = makeErrStr("err");
                }
                end = iss.start + iss.len;
                // don't mark 2 issues in one place
                if ((iss.start < start0) && (end <= start0)) {
                    let message = previewMessages[iss.code].replace("%s", iss.subText);
                    start0 = iss.start;
                    tArray.splice(end, 0, endSpan);
                    tArray.splice(start0, 0, errorString + message + endSpan);
                }
            }
            // since inserting the markups moves all later parts of the array up
            // we must start from the last one

            tArray.forEach(htmlEncodeChar);
            issArray.forEach(markIss);
            txt = tArray.join("");  // join it back into a string
        }



        // add style and optional colouring for marked-up text
        // this works on text which has already had < and > encoded as &lt; &gt;
        function showStyle() {
            var colorString0, colorString; // for out-of-line tags, tb, sub- and super-scripts
            var sc1 = "&lt;sc&gt;";
            var sc2 = "&lt;/sc&gt;";
            var noteStringOr = "\\[\\*\\*[^\\]]*\\]|"; // a user note
            // a user note or string of small capitals
            var sc_re = new RegExp(noteStringOr + sc1 + "([^]+?)" + sc2, 'g');
            var noNote;

            function transformSC(match, p1) { // if all upper case transform to lower
                if (!p1) { // must be user note
                    return match;
                }
                noNote = removeComments(p1);
                // remove tags so that all uppercase string is correctly identified
                noNote = noNote.replace(/&lt;\/?.&gt;/g, '');
                if (noNote === noNote.toUpperCase()) { // found no lower-case
                    return sc1 + '<span class="tt">' + p1 + endSpan + sc2;
                } else {
                    return match;
                }
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

                if (!p2) { // must be user note
                    return match;
                }
                var tagMark = "";
                switch (viewMode) {
                case "show_tags":
                    tagMark = match;
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
                // if flat do not apply style (some change the width)
                var styleClass;
                if (viewMode === "flat") {
                    styleClass = " ";
                } else {
                    styleClass = " class='" + p2 + "' ";
                }
                return "<span" + styleClass + makeColourStyle(p2) + '>' + tagMark;
            }

            // inline tags
            // the way html treats small cap text is different to the dp convention
            // so if sc-marked text is all upper-case transform to lower
            if (viewMode !== "flat") {
                txt = txt.replace(sc_re, transformSC);
            }
            // find user note or inline tag
            var reTag = new RegExp(noteStringOr + "&lt;(\\/?)(" + ILTags + ")&gt;", "g");
            txt = txt.replace(reTag, spanStyle);
            // out of line tags
            colorString0 = makeColourStyle('etc');
            colorString = colorString0 + '>$&</span>';
            if (!wrapMode && styler.color) {    // not re-wrap and colouring
                txt = txt.replace(/\/\*|\*\/|\/#|#\/|&lt;tb&gt;/g, '<span' + colorString);
            }
            if (viewMode !== "show_tags") {
                colorString = colorString0 + '>$1</span>';
            }

            // show sub- and super-scripts
            function showSubSuper(text) {
                let textOut = text.replace(/_\{(.+?)\}/g, '<span class="sub"' + colorString);
                textOut = textOut.replace(/\^\{(.+?)\}/g, '<span class="sup"' + colorString);
                // single char superscript -  any char except {
                // do not allow < as a single char superscript
                // incase it's a tag which would give overlapping markup
                return textOut.replace(/\^([^{<])/g, '<span class="sup"' + colorString);
            }

            // do not process sub- and super-scripts inside math markup
            txt = processExMath(txt, showSubSuper, styler.allowMathPreview);
        }

        // attempt to make an approximate representation of formatted text
        // remove comments, use numbers of blank lines to mark headings and
        // sub-headings, re-wrap except for no-wrap markup
        function reWrap() {
            var blankLines = 0; // counts the number of blank lines which we have passed
            var index = 0;  // counts lines
            var subHeading = false;
            var txtLines = [];
            var lines;
            var inNoWrap = false;
            var newPage = true; // use no indent if no blank line before text at start of page
            var inDiv = false;  // so can put in </div> if find blank line or at end

            // split the text into an array of lines
            txtLines = txt.split('\n');
            txt = "";
            lines = txtLines.length;

            function processLine() {
                var textLine = txtLines[index];
                // trailing space will have been removed earlier by removeCommentLines()
                if (textLine === "") {
                    newPage = false;
                    if (inNoWrap) {
                        txt += "\n";
                        return;
                    }
                    if ((blankLines === 0) && (inDiv)) {
                        txt += "</div>";
                        inDiv = false;
                    }
                    blankLines += 1;
                    return;
                }
                // remove embedded comments
                textLine = removeComments(textLine);
                if (textLine === "") {
                    return; // whole line is comment, do nothing
                }

                if (textLine === "&lt;tb&gt;") {    // thought break
                    txt += '<div class="tb"></div>';
                    blankLines = 0; // so the following one makes it 1, giving a paragraph
                    return;
                }
                if (textLine === "/#") {
                    txt += '<div class="bq">\n';
                    return;
                }
                if (textLine === "#/") {
                    txt += "</div>"; // to end the bq
                    return;
                }
                if (textLine === "/*") {
                    newPage = false;
                    txt += '<div class="nw">';
                    blankLines = 0;
                    subHeading = false;
                    inNoWrap = true;
                    return;
                }
                if (textLine === "*/") {
                    txt += "</div>"; // to end the nw
                    inNoWrap = false;
                    blankLines = 0;  // start over with count
                    return;
                }
                // ordinary text
                switch (blankLines) {
                case 4: // heading
                    txt += '<div class="head2">' + textLine + '\n';
                    inDiv = true;
                    subHeading = true;    // next thing
                    break;
                    // 2 blank lines can introduce a paragraph after heading or subheading,
                    // or a section heading or a section without a heading.
                    // if it's the last line (when it could be "The End") or the following line is blank
                    // treat is as a section heading,
                    // otherwise a paragraph
                case 2:
                    if ((lines === (index + 1)) || (txtLines[index + 1] === "")) {
                        txt += '<div class="head4">' + textLine + '\n';
                    } else {
                        txt += '<div class="para">' + textLine + '\n';
                    }
                    inDiv = true;
                    subHeading = false;
                    break;
                case 1:
                    if (subHeading) {
                        txt += '<div class="head3">';
                    } else {
                        txt += '<div class="para">';
                    }
                    txt += (textLine + "\n");
                    inDiv = true;
                    break;
                case 0:     // in middle of para or at start of page
                    if (newPage && !inNoWrap) {  // at page start
                        txt += '<div class="mid_para">';  // no indent
                        inDiv = true;
                    }
                    newPage = false;
                    txt += (textLine + "\n");
                    break;
                default:
                    break;
                }
                blankLines = 0;
            }

            while (index < lines) {
                processLine();
                index += 1;
            }
            if (inDiv) {   // after end
                txt += "</div>";
            }
        }

        // these store the removed comment lines for later re-insertion
        var comLine = [];
        var comIndex = [];

        function removeCommentLines() {
            function htmlEncodeString(s) {
                return s.replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;");
            }

            var txtLines = [];
            var index;  // counts lines
            var tempLine;

            // split the text into an array of lines
            txtLines = txt.split('\n');
            index = txtLines.length - 1;
            // splice changes txtLines so start from end and work backwards
            while (index >= 0) {
            // remove trailing space
                tempLine = txtLines[index].replace(/\s+$/, "");
                txtLines[index] = tempLine;
                // ignore lines which are entirely comment and space
                if ("" !== tempLine) {
                    if (!nonComment(tempLine)) {
                        txtLines.splice(index, 1);
                        comLine.push(htmlEncodeString(tempLine));
                        comIndex.push(index);
                    }
                }
                index -= 1;
            }
            txt = txtLines.join("\n");
        }

        function restoreCommentLines() {
            var txtLines = [];
            // split the text into an array of lines
            txtLines = txt.split('\n');
            var ix = comIndex.length - 1;
            // insert first comment line, which is store last in comLine, first
            // then subsequent lines in txtLines will be shifted up so that lines
            // from comLines will be inserted in the right place
            while (ix >= 0) {
                txtLines.splice(comIndex[ix], 0, comLine[ix]);
                ix -= 1;
            }
            txt = txtLines.join("\n");
        }

        // remove trailing whitespace
        txt = txt.replace(/\s+$/, "");

        // remove lines which are entirely comments to simplify checking
        // where there should be blank lines
        // we need to encode html in these lines. Could encode everything at start
        // but then problems e.g. marking the character after 3 blank lines if
        // encoded <  as &lt, so treat these lines separately.
        removeCommentLines();
        let issArray = analyse(txt, styler);
        addMarkUp(issArray);
        restoreCommentLines();

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
});

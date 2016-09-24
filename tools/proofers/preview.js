/*global previewMessages */
/*
This function checks the text for formatting issues and adds the markup
for colouring and issue highlighting.
It can be used alone with a simple html interface for testing.
The external references are previewMessages which is loaded by the function
output_preview_strings() defined in preview.inc, called in preview_strings.php
and previewControl.adjustMargin() defined in previewControl.js
txt is the text to analyse.
viewMode determines if the inline tags are to be shown or hidden and whether to
re-wrap the text.
styler is an object containing colour and font options.
*/
var makePreview = function (txt, viewMode, styler) {
    "use strict";
    // 1 means a definite issue, 0 a possible issue
    var issueType = {
        noStartTag: 1,
        noEndTag: 1,
        noEndTagInPara: 1,
        misMatchTag: 1,
        nestedTag: 1,
        unRecTag: 0,
        tabChar: 1,
        blankBefore: 1,
        blankAfter: 0,
        NWinNW: 1,
        BQinNW: 1,
        aloneTag: 1,
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
        footnoteId: 0
    };

    var endSpan = "</span>"; // a constant string
    var issueCount = [0, 0]; // possible issues, issues
    var issArray = []; // stores issues for markup-insertion later

    function reportIssue(start, len, code) {
        issueCount[issueType[code]] += 1;
        issArray.push({start: start, len: len, msg: previewMessages[code], type: issueType[code]});
    }

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

    // we must avoid two issues giving overlapping markup
    // sort them first and mark from end towards beginning
    // sort them so that if two start in same place, then the more serious is
    // marked first
    function addMarkUp() {
        // start0 is start of previous issue to check if 2 issues overlap
        // initially a large number so it works 1st time
        var start0 = 100000;
        var end;
        var errorString;
        // split up the string into an array of characters
        var tArray = txt.split("");

        function htmlEncode(s, i) {
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
                start0 = iss.start;
                tArray.splice(end, 0, endSpan);
                tArray.splice(start0, 0, errorString + iss.msg + endSpan);
            }
        }
        // since inserting the markups moves all later parts of the array up
        // we must start from the last one
        issArray.sort(function (a, b) {
            var diff = b.start - a.start;   // last first
            if (diff === 0) {
                return b.type - a.type;
            } else {
                return diff;
            }
        });

        tArray.forEach(htmlEncode);
        issArray.forEach(markIss);
        txt = tArray.join("");  // join it back into a string
    }

    function removeComments(textLine) {
        return textLine.replace(/\[\*\*[^\]]*\]/g, '');
    }

    // true if txtLine contains anything except only comments or spaces
    function nonComment(textLine) {
        return (/\S/.test(removeComments(textLine)));
    }

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

    // find end of line (or eot) following ix
    function findEnd(ix) {
        var re = /\n|$/g;
        var result;
        re.lastIndex = ix;
        result = re.exec(txt);
        return result.index;
    }

    // check for chars before tag or on previous line
    function chkBefore(start, len) {
        if ((/./.test(txt.charAt(start - 1))) || (/./.test(txt.charAt(start - 2)))) {
            reportIssue(start, len, "blankBefore");
        }
    }

    // check no non-comment chars follow on same line and next line is blank
    function chkAfter(start, len) {
        var ix = start + len;
        var end = findEnd(ix);
        if (nonComment(txt.slice(ix, end)) || (/./.test(txt.charAt(end + 1)))) {
            reportIssue(start, len, "blankAfter");
        }
    }

    // check that no other characters are on the same line
    function chkAlone(start, len) {
        var ix = start + len;
        if (nonComment(txt.slice(ix, findEnd(ix))) || (/./.test(txt.charAt(start - 1)))) {
            reportIssue(start, len, "aloneTag");
        }
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

        while (true) {
            result = oolre.exec(txt);  // find next tag
            if (null === result) { // no tag found
                // if there are any tags on the stack mark them as errors
                while (tagStack.length !== 0) {
                    stackTop = tagStack.pop();
                    reportIssue(stackTop.start, 2, "noEndTag");
                }
                return;
            }
            start = result.index;
            tagString = result[0];

            chkAlone(start, 2);
            // for an opening tag check previous line is blank
            // or an opening block quote tag possibly with a comment
            if ((tagString.charAt(0) === "/") && (start > 1) && (txt.charAt(start - 2) !== "\n")) {
                if ("/#" !== removeComments(findPrevLine(start))) {
                    reportIssue(start, 2, "OolPrev");
                }
            }
            // for a closing tag check following line is blank
            // or a closing block quote or ] (ending a footnote).
            if (tagString.charAt(1) === "/") {
                if (/[^#\n\]]/.test(txt.charAt(findEnd(start + 2) + 1))) {
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
        var stackTop;
        var preChar;
        var postChar;

        // find index in stack of ntag, return -1 if none found
        // internet explorer does not support array.find so use this function
        function stackFind(ntag) {
            var tagData;
            var i = tagStack.length - 1;
            while (i >= 0) {
                tagData = tagStack[i];
                if (tagData.tag === ntag) {
                    break;
                }
                i -= 1;
            }
            return i;
        }

        // regex to match valid inline tags or a blank line
        var re = /<(\/?)([ibfg]|sc)>|\n\n/g;

        while (true) {
            result = re.exec(txt);
            if (null === result) {
                while (tagStack.length !== 0) {
                    stackTop = tagStack.pop();
                    reportIssue(stackTop.start, stackTop.tagLen, "noEndTag");
                }
                return;
            }
            if (result[0] === "\n\n") {
                while (tagStack.length !== 0) {
                    stackTop = tagStack.pop();
                    reportIssue(stackTop.start, stackTop.tagLen, "noEndTagInPara");
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
                if (/\w/.test(postChar)) { // char after end tag
                    reportIssue(end, 1, "charAfterEnd");
                }
                if (tagStack.length === 0) {    // missing start tag
                    reportIssue(start, tagLen, "noStartTag");
                } else {
                    stackTop = tagStack.pop();
                    if (stackTop.tag !== tagString) {
                        reportIssue(start, tagLen, "misMatchTag");
                        reportIssue(stackTop.start, stackTop.tagLen, "misMatchTag");
                    }
                }
            } else {    // startTag
                if (stackFind(tagString) >= 0) { // check if any already in stack
                    reportIssue(start, tagLen, "nestedTag");
                }
                if (/[,.;:!\?]/.test(postChar)) {
                    reportIssue(end, 1, "puncAfterStart");
                }
                if (postChar === " ") {
                    reportIssue(end, 1, "spaceAfterStart");
                }
                if (postChar === "\n") {
                    reportIssue(start, tagLen, "nlAfterStart");
                }
                if (/\w|[,.;:]/.test(preChar)) { // non-space before start tag
                    reportIssue(start - 1, 1, "charBeforeStart");
                }
                tagStack.push({tag: tagString, start: start, tagLen: tagLen});
            }
        }
    }

    // check for an unrecognised tag
    function unRecog() {
        var re = /<(?!(?:\/?(?:[ibfg]|sc)|tb)>)/g;
        var result;
        while (true) {
            result = re.exec(txt);
            if (null === result) {
                break;
            }
            reportIssue(result.index, 1, "unRecTag");
        }
    }

    // check for no upper case between small caps tags
    function checkSC() {
        var result;
        var start;
        var re = /<sc>([^]+?)<\/sc>/g;
        while (true) {
            result = re.exec(txt);
            if (null === result) {
                return;
            }
            if (result[1] === result[1].toLowerCase()) {  //no upper case found
                start = result.index;
                reportIssue(start, 4, "scNoCap");
            }
        }
    }

    // find tab characters
    function checkTab() {
        var re = /\t/g;
        var result;
        while (true) {
            result = re.exec(txt);
            if (null === result) {
                break;
            }
            reportIssue(result.index, 1, "tabChar");
        }
    }

    // add style and optional colouring for marked-up text
    // this works on text which has already had < and > encoded as &lt; &gt;
    function showStyle() {
        var etcstr; // for out-of-line tags, tb, sub- and super-scripts
        var repstr2 = endSpan;
        var sc1 = "&lt;sc&gt;";
        var sc2 = "&lt;\/sc&gt;";
        var sc_re = new RegExp(sc1 + "([^]+?)" + sc2, 'g'); // a string of small capitals

        function transformSC(match, p1) { // if all upper case transform to lower
            if (p1 === p1.toUpperCase()) { // found no lower-case
                return sc1 + '<span class="tt">' + p1 + endSpan + sc2;
            } else {
                return match;
            }
        }

        function spanStyle(match, p1) {
            var str = '<span class="' + p1 + '"' + makeColourStyle(p1) + '>';
            if (viewMode === "show_tags") {
                str += match;
            }
            return str;
        }
        // inline tags
        if (viewMode === "show_tags") {
            repstr2 = "$&" + repstr2;
        }
        // the way html treats small cap text is different to the dp convention
        // so if sc-marked text is all upper-case transform to lower
        txt = txt.replace(sc_re, transformSC);
        txt = txt.replace(/&lt;(i|b|g|f|sc)&gt;/g, spanStyle)
            .replace(/&lt;\/(i|b|g|f|sc)&gt;/g, repstr2);

        // out of line tags
        etcstr = makeColourStyle('etc');
        if (viewMode === "show_tags") {
            etcstr += '>$&</span>';
        } else {
            etcstr += '>$1</span>';
        }
        if ((viewMode !== "re_wrap") && styler.color) {    // not re-wrap and colouring
            txt = txt.replace(/(\/\*|\*\/|\/#|#\/|&lt;tb&gt;)/g, '<span' + etcstr);
        }
        // sub- and super-scripts
        txt = txt.replace(/_\{([^\}]*)\}/g, '<span class="sub"' + etcstr);
        txt = txt.replace(/\^\{([^\}]*)\}/g, '<span class="sup"' + etcstr);
        // single char superscript -  any char except {
        // do not allow < incase it's a tag which would screw up
        txt = txt.replace(/\^([^\{<])/g, '<span class="sup"' + etcstr);
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
            // if the following line is blank assume it's a section heading,
            // otherwise a paragraph
            case 2:
                if (txtLines[index + 1] === "") {
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

    function checkBlankNumber() { // only 1, 2 or 4 blank lines should appear
        var result;
        var end;
        var re = /^\n{3}.|.\n{4}.|^\n{5,}.|.\n{6,}./g;

        while (true) {
            result = re.exec(txt);
            if (null === result) {
                break;
            }
            re.lastIndex -= 1; // in case only one char, include it in next search
            end = result.index + result[0].length;
            reportIssue(end - 1, 1, "blankLines124");
        }
    }

    // there should not be an entire bold single line after 2 or 4 blank lines
    function boldLine() {
        var result;
        var start;
        var re = /((?:^|\n)\n\n<b>)(.*?)<\/b>\n\n/g;

        while (true) {
            result = re.exec(txt);
            if (null === result) {
                break;
            }
            re.lastIndex -= 2;  // so can find another straight after
            start = result.index + result[1].length;
            reportIssue(start, result[2].length, "noBold");
        }
    }

    function checkFootnoteId() {
        var result;
        var re = /\[Footnote *\:/g;
        var start;
        while (true) {
            result = re.exec(txt);
            if (null === result) {
                return;
            }
            start = result.index;
            if (txt.charAt(start - 1) !== "*") {
                reportIssue(start, result[0].length, "footnoteId");
            }
        }
    }

    // find index of next unmatched ] return 0 if none found
    function findClose(index) {
        var result;
        var nestLevel = 0;
        var re = /\[|\]/g;  // [ or ]
        re.lastIndex = index;
        while (true) {
            result = re.exec(txt);
            if (null === result) {
                return 0;
            }
            if ("[" === result[0]) {
                nestLevel += 1;
            } else { // must be ]
                if (0 === nestLevel) {
                    return result.index;
                }
                nestLevel -= 1;
            }
        }
    }

    // check blank lines before and after footnote etc.
    function checkBlankLines() {
        var result, start, len, end, end1;
        var re = /\*?\[(Footnote|Sidenote|Illustration)/g;
        while (true) {
            result = re.exec(txt);
            if (null === result) {
                break;
            }
            start = result.index;
            len = result[0].length;
            end = start + len;
            chkBefore(start, len);

            end1 = findClose(end);
            if (0 === end1) { // no ] found
                reportIssue(start, len, "noCloseBrack");
            } else {
                end = end1 + 1;
                len = 1;
                if (txt.charAt(end) === "*") { // allow * after
                    end += 1;
                    len += 1;
                }
                chkAfter(end1, len);
            }
        }
        re = /<tb>/g;
        while (true) {
            result = re.exec(txt);
            if (null === result) {
                break;
            }
            start = result.index;
            len = result[0].length;
            chkBefore(start, len);
            chkAfter(start, len);
        }
    }

    // these store the removed comment lines for later re-insertion
    var comLine = [];
    var comIndex = [];

    function removeCommentLines() {
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
                    comLine.push(tempLine);
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

    // return true if no errors which would cause showstyle() or reWrap() to fail
    function check() {
        parseInLine();
        // if inline parse fails then these checks might not work
        if (0 === issueCount[1]) {
            checkSC();
            boldLine();
        }
        parseOol();
        checkFootnoteId();
        checkBlankNumber();
        unRecog();
        checkTab();
        checkBlankLines();
        return (issueCount[1] === 0);
    }
    // remove lines which are entirely comments to simplify checking
    // where there should be blank lines
    removeCommentLines();
    var ok = check();
    addMarkUp();
    restoreCommentLines();
    if (ok) {
        showStyle();
        if (viewMode === "re_wrap") {
            reWrap();
        }
    }

    return {
        ok: ok,
        txtout: txt,
        issues: issueCount[1],
        possIss: issueCount[0]
    };
};

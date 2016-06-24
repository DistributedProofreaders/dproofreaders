/*global previewMessages */
/* This function checks the text for formatting issues and adds the markup for colouring and issue highlighting.
It can be used alone with a simple html interface for testing.
The external references are previewMessages which is loaded by the function output_preview_strings() defined in preview.inc, called in preview_strings.php
and previewControl.adjustMargin() defined in previewControl.js
*/
var makePreview = function (txt, viewMode, styler) {
    "use strict";
    var endSpan = "</span>"; // a constant string
    var issueCount = [0, 0]; // poss, iss
    var issArray = []; // stores issues while checking for markup-insertion later

    function reportIssue(start, len, msg, type) {
        issueCount[type] += 1;
        issArray.push({start: start, len: len, msg: msg, type: type});
    }

    function makeColourStyle(s) {
        var style = styler[s];
        var have_style = false;
        var str = "";
        if (!styler.color && (s !== "err") && (s !== "hlt")) {   // style issues always
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

    function addMarkUp() {
        var start0 = 100000;    // start of previous issue to check if 2 issues in same place, large so 1st works
        var end;
        var errorString;
        var tArray = txt.split("");     // split up the string to an array of characters

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
            if ((iss.start !== start0) && (end <= start0)) {  // don't mark 2 in one place
                start0 = iss.start;
                tArray.splice(end, 0, endSpan);
                tArray.splice(start0, 0, errorString + iss.msg + endSpan);
            }
        }
// since inserting the markups moves all later parts of the array up we must start from the last one
        issArray.sort(function (a, b) {
            return b.start - a.start;   // last first
        });

        tArray.forEach(htmlEncode);
        issArray.forEach(markIss);
        txt = tArray.join("");  // join it back into a string
    }

// cases for tag on stack top:
// none: /* or /# -> push, else error
// /*: */ pop, #/ -> mismatch, /# -> BQ not allowed inside NW, /* -> NW inside NW
// /#: /# or /* -> push, #/ -> pop, */ mismatch

    function chkAlone(start, len) { // check that no other characters are on the same line
        if (/./.test(txt.charAt(start + len)) || (/./.test(txt.charAt(start - 1)))) { // character before or after tag
            reportIssue(start, len, previewMessages.aloneTag, 1);
        }
    }

// the parsers for inline and out-of-line tags work with a stack:
// for correct nesting opening tags are pushed onto the stack and popped off when a corresponding closing tag is found
// parse the out-of-line tags
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
                while (tagStack.length !== 0) { // if there are any tags on the stack mark them as errors
                    stackTop = tagStack.pop();
                    reportIssue(stackTop.start, 2, previewMessages.noEndTag, 1);
                }
                return;
            }
            start = result.index;
            tagString = result[0];

            chkAlone(start, 2);
// for an opening tag check previous line is blank (or another opening block quote tag or ])
            if ((tagString.charAt(0) === "/") && (/[^#\n\]]/.test(txt.charAt(start - 2)))) {
                reportIssue(start, 2, previewMessages.OolPrev, 1);
            }
// for a closing tag check following line is blank or a closing block quote or ]
            if ((tagString.charAt(1) === "/") && (/[^#\n\]]/.test(txt.charAt(start + 3)))) {
                reportIssue(start, 2, previewMessages.OolNext, 1);
            }

            if (tagStack.length === 0) {
                if ('/' === tagString.charAt(0)) {     // start tag
                    tagStack.push({tag: tagString, start: start});
                } else {
                    reportIssue(start, 2, previewMessages.noStartTag, 1);
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
                        reportIssue(start, 2, previewMessages.misMatchTag, 1);   // mark last first
                        reportIssue(stackTop.start, 2, previewMessages.misMatchTag, 1);
                        break;
                    case "/*": // open NW
                        reportIssue(start, 2, previewMessages.NWinNW, 1);
                        tagStack.push({tag: tagString, start: start});
                        break;
                    default:    // open BQ
                        reportIssue(start, 2, previewMessages.BQinNW, 1);
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
                        reportIssue(start, 2, previewMessages.misMatchTag, 1);   // mark last first
                        reportIssue(stackTop.start, 2, previewMessages.misMatchTag, 1);
                        break;
                    default:    // open either
                        tagStack.push({tag: tagString, start: start});
                        break;
                    }
                }
            }
        }
    }

// if start tag, check if any same already in stack, push onto stack
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

        var re = /<(\/?)([ibfg]|sc)>|\n\n/g;  // match valid inline tags or blank line

        while (true) {
            result = re.exec(txt);
            if (null === result) {
                while (tagStack.length !== 0) {
                    stackTop = tagStack.pop();
                    reportIssue(stackTop.start, stackTop.tagLen, previewMessages.noEndTag, 1);
                }
                return;
            }
            if (result[0] === "\n\n") {
                while (tagStack.length !== 0) {
                    stackTop = tagStack.pop();
                    reportIssue(stackTop.start, stackTop.tagLen, previewMessages.noEndTagInPara, 1);
                }
                continue;
            }
            start = result.index;
            tagLen = result[0].length;
            end = start + tagLen;
            tagString = result[2];
            if (result[1] === '/') {    // end tag
                if (/[,;:]/.test(txt.charAt(start - 1)) && (txt.length !== end)) { // , ; or : before end tag except at eot
                    reportIssue(start - 1, 1, previewMessages.puncBEnd, 1);
                }
                if (txt.charAt(start - 1) === " ") {
                    reportIssue(start - 1, 1, previewMessages.spaceBeforeEnd, 1);
                }
                if (txt.charAt(start - 1) === "\n") {
                    reportIssue(start, tagLen, previewMessages.nlBeforeEnd, 1);
                }
                if (/\w/.test(txt.charAt(end))) { // char after end tag
                    reportIssue(end, 1, previewMessages.charAfterEnd, 0);
                }
                if (tagStack.length === 0) {    // missing start tag
                    reportIssue(start, tagLen, previewMessages.noStartTag, 1);
                } else {
                    stackTop = tagStack.pop();
                    if (stackTop.tag !== tagString) {
                        reportIssue(start, tagLen, previewMessages.misMatchTag, 1);
                        reportIssue(stackTop.start, stackTop.tagLen, previewMessages.misMatchTag, 1);
                    }
                }
            } else {    // startTag
                if (stackFind(tagString) >= 0) {   // check if any already in stack
                    reportIssue(start, tagLen, previewMessages.nestedTag, 1);
                }
                if (/[,.;:!\? ]/.test(txt.charAt(end))) {
                    reportIssue(end, 1, previewMessages.spaceAfterStart, 1);
                }
                if (txt.charAt(end) === "\n") {
                    reportIssue(start, tagLen, previewMessages.nlAfterStart, 1);
                }
                if (/\w|[,.;:]/.test(txt.charAt(start - 1))) { // non-space before start tag
                    reportIssue(start - 1, 1, previewMessages.charBeforeStart, 0);
                }
                tagStack.push({tag: tagString, start: start, tagLen: tagLen});
            }
        }
    }

// check for an unrecognised tag
    function unRecog() {
        var re = /<(?!(?:\/?(?:[ibfg]|sc)|tb)>)/g;
        var result;
        while (result = re.exec(txt)) {
            reportIssue(result.index, 1, previewMessages.unRecTag, 0);
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
            if (-1 === result[1].search(/[A-Z]|[À-Ö]|[Ø-Þ]/)) {  //no upper case found - need to extend this for utf8
                start = result.index + 4;
                reportIssue(start, result[1].length, previewMessages.scNoCap, 1);
            }
        }
    }

// find tab characters
    function checkTab() {
        var re = /\t/g;
        var result;
        while (result = re.exec(txt)) {
            reportIssue(result.index, 1, previewMessages.tabChar, 1);
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
            if (-1 === p1.search(/[a-z]|[ß-ö]|[ø-ÿ]/)) {    // found no lower-case -- need to extend this for utf8
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
        txt = txt.replace(/_\{([A-Za-z0-9]*)\}/g, '<span class="sub"' + etcstr);
        txt = txt.replace(/\^\{([A-Za-z0-9]*)\}/g, '<span class="sup"' + etcstr);
        txt = txt.replace(/\^([A-Za-z0-9])/g, '<span class="sup"' + etcstr);
    }

// attempt to make an approximate representation of formatted text
// remove comments, use numbers of blank lines to mark headings and sub-headings
// re-wrap except for no-wrap markup
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
            if (/^\[\*\*[^\]]*\]$/.test(textLine)) {    // whole line is comment, do nothing
                return;
            }
            textLine = textLine.replace(/\[\*\*[^\]]*\]/g, ''); // remove embedded comments
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
            if (textLine === "&lt;tb&gt;") {    // thought break
                txt += '<div class="tb"></div>';
                blankLines = 0;    // so the following one makes it 1, giving a paragraph
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
                blankLines = 0;    // start over with count
                return;
            }
// ordinary text
            switch (blankLines) {
            case 4: // heading
                txt += '<div class="head2">' + textLine + '\n';
                inDiv = true;
                subHeading = true;    // next thing
                break;
// 2 blank lines can introduce a paragraph after heading or subheading, or a section heading or a section without a heading
// if the following line is blank assume its a section heading, else a paragraph
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
            re.lastIndex -= 1;  // in case only one char, include it in next search
            end = result.index + result[0].length;
            reportIssue(end - 1, 1, previewMessages.blankLines, 1);
        }
    }

    function boldLine() { // there should not be an entire bold single line after 2 or 4 blank lines
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
            reportIssue(start, result[2].length, previewMessages.noBold, 0);
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
                reportIssue(start, result[0].length, previewMessages.footnoteId, 0);
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

            if ((/./.test(txt.charAt(start - 1))) || (/./.test(txt.charAt(start - 2)))) {
                reportIssue(start, len, previewMessages.blankBefore, 1);
            }

            end1 = findClose(end);
            if (0 === end1) { // no ] found
                reportIssue(start, len, previewMessages.noCloseBrack, 0);
            } else {

                end = end1 + 1;
                len = 1;
                if (txt.charAt(end) === "*") { // allow * after
                    end += 1;
                    len += 1;
                }
                if ((/./.test(txt.charAt(end))) || (/./.test(txt.charAt(end + 1)))) {
                    reportIssue(end1, len, previewMessages.blankAfter, 1);
                }
            }
        }
        re = /<tb>/g;
        while (result = re.exec(txt)) {
            start = result.index;
            len = result[0].length;
            chkAlone(start, len);
            if (/./.test(txt.charAt(start - 2))) {
                reportIssue(start, len, previewMessages.blankBefore, 1);
            }
            if (/./.test(txt.charAt(start + len + 1))) {
                reportIssue(start, len, previewMessages.blankAfter, 1);
            }
        }
    }

    function encodeWhite() { // \n -> <br>, spaces -> &nbsp; except last
        txt = txt.replace(/\n/g, "<br>")
            .replace(/  /g, "&nbsp;&nbsp;");
    }

    function check() {  // return true if no errors which would cause showstyle() or reWrap() to fail
        parseInLine();
        if (0 === issueCount[1]) {  // if inline parse fails then these checks might not work
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

    var ok = check();
    addMarkUp();
    if (ok) {
        showStyle();
        if (viewMode === "re_wrap") {
            reWrap();
        }
    }
    if (viewMode !== "re_wrap") {
        encodeWhite();  // this is not necessary if just displaying in a "pre" but it allows it to be edited correctly in a "contenteditable pre"
    }

    return {
        ok: ok,
        txtout: txt,
        issues: issueCount[1],
        possIss: issueCount[0]
    };
};

var makePreview = function (txt, vtype, styler, msg) {
    "use strict";
    var endSpan = "</span>";
    var issCount = [0, 0];   // poss, iss
    var issArray = [];

    function reportIssue(start, len, msg, type) {
        issCount[type] += 1;
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

//    var errstr = makeErrStr("err");
//    var hltstr = makeErrStr("hlt");

    function addMarkUp() {
        var start0 = 100000;    // start of previous issue to check if 2 issues in same place, large so 1st works
        var end;
        var errorString;
        var tArray = txt.split("");     // string as array

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

        issArray.sort(function (a, b) {
            return b.start - a.start;   // last first
        });

        tArray.forEach(htmlEncode);
        issArray.forEach(markIss);
        txt = tArray.join("");
    }

// cases for tag on stack top:
// none: /* or /# -> push, else error
// /*: */ pop, #/ -> mismatch, /# -> BQ not allowed inside NW, /* -> NW inside NW
// /#: /# or /* -> push, #/ -> pop, */ mismatch

    function chkAlone(start, len) {
        if (/./.test(txt.charAt(start + len)) || (/./.test(txt.charAt(start - 1)))) { // character before or after tag
            reportIssue(start, len, msg.aloneTag, 1);
        }
    }

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
                while (tagStack.length !== 0) {
                    stackTop = tagStack.pop();
                    reportIssue(stackTop.start, 2, msg.noEndTag, 1);
                }
                return;
            }
            start = result.index;
            tagString = result[0];

            chkAlone(start, 2);
            if ((tagString.charAt(0) === "/") && (/[^#\n\]]/.test(txt.charAt(start - 2)))) { // previous already tested to be nl, # or ] ok
                reportIssue(start, 2, msg.OolPrev, 1);
            }
            if ((tagString.charAt(1) === "/") && (/[^#\n\]]/.test(txt.charAt(start + 3)))) {
                reportIssue(start, 2, msg.OolNext, 1);
            }

            if (tagStack.length === 0) {
                if ('/' === tagString.charAt(0)) {     // start tag
                    tagStack.push({tag: tagString, start: start});
                } else {
                    reportIssue(start, 2, msg.noStartTag, 1);
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
                        reportIssue(start, 2, msg.misMatchTag, 1);   // mark last first
                        reportIssue(stackTop.start, 2, msg.misMatchTag, 1);
                        break;
                    case "/*": // open NW
                        reportIssue(start, 2, msg.NWinNW, 1);
                        tagStack.push({tag: tagString, start: start});
                        break;
                    default:    // open BQ
                        reportIssue(start, 2, msg.BQinNW, 1);
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
                        reportIssue(start, 2, msg.misMatchTag, 1);   // mark last first
                        reportIssue(stackTop.start, 2, msg.misMatchTag, 1);
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
                    reportIssue(stackTop.start, stackTop.tagLen, msg.noEndTag, 1);
                }
                return;
            }
            if (result[0] === "\n\n") {
                while (tagStack.length !== 0) {
                    stackTop = tagStack.pop();
                    reportIssue(stackTop.start, stackTop.tagLen, msg.noEndTagInPara, 1);
                }
                continue;
            }
            start = result.index;
            tagLen = result[0].length;
            end = start + tagLen;
            tagString = result[2];
            if (result[1] === '/') {    // end tag
                if (/[,;:]/.test(txt.charAt(start - 1)) && (txt.length !== end)) { // , ; or : before end tag except at eot
                    reportIssue(start - 1, 1, msg.puncBEnd, 0);
                }
                if (txt.charAt(start - 1) === " ") {
                    reportIssue(start - 1, 1, msg.spaceBeforeEnd, 0);
                }
                if (txt.charAt(start - 1) === "\n") {
                    reportIssue(start, tagLen, msg.nlBeforeEnd, 1);
                }
                if (/\w/.test(txt.charAt(end))) { // char after end tag
                    reportIssue(end, 1, msg.charAfterEnd, 0);
                }
                if (tagStack.length === 0) {    // missing start tag
                    reportIssue(start, tagLen, msg.noStartTag, 1);
                } else {
                    stackTop = tagStack.pop();
                    if (stackTop.tag !== tagString) {
                        reportIssue(start, tagLen, msg.misMatchTag, 1);   // mark last first
                        reportIssue(stackTop.start, stackTop.tagLen, msg.misMatchTag, 1);
                    }
                }
            } else {    // startTag
                if (stackFind(tagString) >= 0) {   // check if any already in stack
                    reportIssue(start, tagLen, msg.nestedTag, 1);
                }
                if (/[,.;:!\? ]/.test(txt.charAt(end))) {
                    reportIssue(end, 1, msg.spaceAfterStart, 0);
                }
                if (txt.charAt(end) === "\n") {
                    reportIssue(start, tagLen, msg.nlAfterStart, 1);
                }
                if (/\w|[,.;:]/.test(txt.charAt(start - 1))) { // non-space before start tag
                    reportIssue(start - 1, 1, msg.charBeforeStart, 0);
                }
                tagStack.push({tag: tagString, start: start, tagLen: tagLen});
            }
        }
    }

    function unRecog() {
        var re = /<(?!(?:\/?(?:[ibfg]|sc)|tb)>)/g;
        var result;
        while (result = re.exec(txt)) {
            reportIssue(result.index, 1, msg.unRecTag, 0);
        }
    }

// check for no upper case between sc tags
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
                reportIssue(start, result[1].length, msg.scNoCap, 1);
            }
        }
    }

    function checkTab() {
        var re = /\t/g;
        var result;
        while (result = re.exec(txt)) {
            reportIssue(result.index, 1, msg.tabChar, 0);
        }
    }

    function showStyle() {
        var etcstr;
        var repstr2 = endSpan;
        var sc1 = "&lt;sc&gt;";
        var sc2 = "&lt;\/sc&gt;";
        var sc_re = new RegExp(sc1 + "([^]+?)" + sc2, 'g'); // a string of small capitals

        function transformSC(match, p1) {
            if (-1 === p1.search(/[a-z]|[ß-ö]|[ø-ÿ]/)) {    // found no lower-case -- need to extend this for utf8
                return sc1 + '<span class="tt">' + p1 + endSpan + sc2;
            } else {
                return match;
            }
        }

        function spanStyle(match, p1) {
            var str = '<span class="' + p1 + '"' + makeColourStyle(p1) + '>';
            if (vtype === "T") {
                str += match;
            }
            return str;
        }
// inline tags
        if (vtype === "T") {
            repstr2 = "$&" + repstr2;
        }
        txt = txt.replace(sc_re, transformSC); // if sc is all upper-case transform to lower
        txt = txt.replace(/&lt;(i|b|g|f|sc)&gt;/g, spanStyle)
            .replace(/&lt;\/(i|b|g|f|sc)&gt;/g, repstr2);

// out of line tags
        etcstr = makeColourStyle('etc');
        if (vtype === "T") {
            etcstr += '>$&</span>';
        } else {
            etcstr += '>$1</span>';
        }
        if ((vtype !== "RW") && styler.color) {    // not re-wrap and colouring
            txt = txt.replace(/(\/\*|\*\/|\/#|#\/|&lt;tb&gt;)/g, '<span' + etcstr);
        }
// sub- and super-scripts
        txt = txt.replace(/_\{([A-Za-z0-9]*)\}/g, '<span class="sub"' + etcstr);
        txt = txt.replace(/\^\{([A-Za-z0-9]*)\}/g, '<span class="sup"' + etcstr);
        txt = txt.replace(/\^([A-Za-z0-9])/g, '<span class="sup"' + etcstr);
    }

    function reWrap() {
        var blankLines = 0;
        var index = 0;     // index
        var subHeading = false;
        var txtLines = [];
        var lines;
        var inNoWrap = false;
        var newPage = true;

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
                if (inNoWrap) {
                    txt += "\n";
                    return;
                }
                if ((blankLines === 0) && (!newPage)) {
                    txt += "</div>";
                }
                newPage = false;
                blankLines += 1;
                return;
            }
            if (textLine === "&lt;tb&gt;") {    // thought break
                txt += '<div class="tb">';  // end div put in by next bl
                blankLines = 0;    // so the following one makes it 1, giving a paragraph
                newPage = false; // so end div gets put in
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
                txt += '<div class="nw">';
                blankLines = 0;    // so no para tag
                newPage = false;
                subHeading = false;
                inNoWrap = true;
                return;
            }
            if (textLine === "*/") {
                inNoWrap = false;   // end nw div will be put in as end para
                blankLines = 0;    // start over with count
                return;
            }
// ordinary text
            switch (blankLines) {
            case 4: // heading
                txt += '<div class="head2">' + textLine + '\n';
                subHeading = true;    // next thing
                break;
            case 2:
                if (!subHeading) { // section heading
                    txt += '<div class="head4">' + textLine + '\n';
                } else {    // para from heading or subheading
                    txt += '<div class="para">' + textLine + '\n';
                }
                subHeading = false;
                break;
            case 1:
                if (subHeading) {
                    txt += '<div class="head3">';
                } else {
                    txt += '<div class="para">';
                }
                txt += (textLine + "\n");
                break;
            case 0:     // in middle of para or
                if (newPage && !inNoWrap) {  // at page start
                    txt += '<div class="mid_para">';  // no indent
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
        if (0 === blankLines) {   // after end
            txt += "</div>";
        }
    }

// blank line checks
    function checkBlankNumber() {
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
            reportIssue(end - 1, 1, msg.blankLines, 1);
        }
    }

    function boldLine() { // entire bold single line after 2 or 4 blank lines
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
            reportIssue(start, result[2].length, msg.noBold, 0);
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
                reportIssue(start, result[0].length, msg.footnoteId, 0);
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
                reportIssue(start, len, msg.blankBefore, 0);
            }

            end1 = findClose(end);
            if (0 === end1) { // no ] found
                reportIssue(start, len, msg.noCloseBrack, 0);
            } else {

                end = end1 + 1;
                len = 1;
                if (txt.charAt(end) === "*") { // allow * after
                    end += 1;
                    len += 1;
                }
                if ((/./.test(txt.charAt(end))) || (/./.test(txt.charAt(end + 1)))) {
                    reportIssue(end1, len, msg.blankAfter, 0);
                }
            }
        }
        re = /<tb>/g;
        while (result = re.exec(txt)) {
            start = result.index;
            len = result[0].length;
            chkAlone(start, len);
            if (/./.test(txt.charAt(start - 2))) {
                reportIssue(start, len, msg.blankBefore, 1);
            }
            if (/./.test(txt.charAt(start + len + 1))) {
                reportIssue(start, len, msg.blankAfter, 1);
            }
        }
    }

    function encodeWhite() { // \n -> <br>, spaces -> &nbsp; except last
        txt = txt.replace(/\n/g, "<br>")
            .replace(/  /g, "&nbsp;&nbsp;");
    }

    function check() {  // return true if no errors which would cause showstyle() or reWrap() to fail
        parseInLine();
        if (0 === issCount[1]) {
            checkSC();
            boldLine();
        }
        parseOol();
        checkFootnoteId();
        checkBlankNumber();
        unRecog();
        checkTab();
        checkBlankLines();
        return (issCount[1] === 0);
    }

    var ok = check();
    addMarkUp();
    if (ok) {
        showStyle();
        if (vtype === "RW") {
            reWrap();
        }
    }
    if (vtype !== "RW") {
        encodeWhite();
    }

    return {
        ok: ok,
        txtout: txt,
        issues: issCount[1],
        possIss: issCount[0]
    };
};

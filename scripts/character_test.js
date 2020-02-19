/*global $ codePoints XRegExp */
/* exported testText */

// this regular expression (constructed below) matches good characters
var goodChars;

// regex unicode property escape is supported in Chrome and Safari (Feb 2020)
// but not in Firefox or Edge. Use 3rd party http://xregexp.com/ instead

// this matches any character: non-mark codepoint followed by 0 or more marks
const charMatch = XRegExp("\\PM\\pM*", "Ag");

// return false if text contains any bad characters
function testText(text) {
    text = text.normalize("NFC");
    let result;
    charMatch.lastIndex = 0;
    while(null != (result = charMatch.exec(text))) {
        if(!goodChars.test(result)) {
            return false;
        }
    }
    return true;
}

$(function () {
    function utf8Chr(codePoint) {
        return "\\u{" + codePoint.slice(2) + "}";
    }

    var charClass = "";
    codePoints.forEach(function (codePoint) {
        if(codePoint.indexOf("-") === -1) {
            // single code point
            charClass += utf8Chr(codePoint);
        } else {
            // range
            let code = codePoint.split("-");
            charClass += utf8Chr(code[0]) + "-" + utf8Chr(code[1]);
        }
    });

    goodChars = new RegExp("^[" + charClass + "]$", "u");
});

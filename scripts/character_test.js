/*global $ codePointRegexCharClass XRegExp */
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
    goodChars = new RegExp(codePointRegexCharClass, "u");
});

/*global $ validCharacterPattern XRegExp */
/* exported testText */

// regex unicode property escape is supported in Chrome and Safari (Feb 2020)
// but not in Firefox or Edge. Use 3rd party http://xregexp.com/ instead

// this matches any character: non-mark codepoint followed by 0 or more marks
const charMatch = XRegExp("\\PM\\pM*", "Ag");

// this regular expression (constructed below) matches individual good characters
var validCharRegex;

function makeValidCharRegex(characterPattern) {
    // need the u flag for Astral plane characters.
    return XRegExp(characterPattern, "Au");
}

$(function () {
    // need to define this after the page has loaded so validCharacterPattern
    // is available
    validCharRegex = makeValidCharRegex(validCharacterPattern);
});

function testChar(character) {
    return validCharRegex.test(character);
}

// return false if text contains any bad characters
function testText(text) {
    text = text.normalize("NFC");
    let result;
    charMatch.lastIndex = 0;
    while(null != (result = charMatch.exec(text))) {
        if(!testChar(result[0])) {
            return false;
        }
    }
    return true;
}

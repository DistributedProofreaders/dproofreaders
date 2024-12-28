/*global validCharRegex XRegExp */
/* exported testText */

// regex unicode property escape is supported in Chrome 64, Safari 11.1
// Firefox 78, Edge 79, Opera 51. Use 3rd party http://xregexp.com/ instead

// this matches any character: non-mark codepoint followed by 0 or more marks
const charMatch = XRegExp("\\PM\\pM*", "Ag");

// return false if text contains any bad characters
function testText(text) {
    text = text.normalize("NFC");
    let result;
    charMatch.lastIndex = 0;
    while (null != (result = charMatch.exec(text))) {
        if (!validCharRegex.test(result[0])) {
            return false;
        }
    }
    return true;
}

/*global validCharRegex */

// this matches any character: non-mark codepoint followed by 0 or more marks
export const charMatch = /\P{M}\p{M}*/gu;

// return false if text contains any bad characters
export function testText(text) {
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

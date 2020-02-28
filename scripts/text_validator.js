/*global $ goodChars charMatch XRegExp */
/* exported validateText */

// goodChars (constructed in character_test.js) matches good unicode characters
// charMatch (constructed in character_test.js) matches any unicode character
// possibly with combining marks: non-mark codepoint + 0 or more mark codes

// this function is copied from dp_proof.js
// could put it in another file misc.js
function htmlSafe(str) {
    // Return a version of str that is safe to send as element-content
    // in an HTML document.
    // That is, make the following replacements:
    //    &  ->  &amp;
    //    <  ->  &lt;
    //    >  ->  &gt;
    // This should be equivalent to PHP's
    //     htmlspecialchars($str,ENT_NOQUOTES)
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

var validateText;

$(function () {
    var textArea = document.getElementById("text_data");

    // check each character, if bad mark or remove it
    function processText(text, clean) {
        var bad = false;

        function charReplacer(match) {
            if(goodChars.test(match)) {
                return match;
            }
            if(clean) {
                return "";
            }
            bad = true;
            return "<span class='bad-char'>" + match + "</span>";
        }

        text = text.replace(charMatch, charReplacer);
        return {processedText: text, valid: !bad};
    }

    function _validateText() {
        var text = textArea.value;
        text = text.normalize("NFC");
        // replace the text with normalised version
        textArea.value = text;
        // convert any markup so does not get interpreted in the checker div
        text = htmlSafe(text);

        let procResult = processText(text, false);
        if(procResult.valid) {
            return true;
        }
        $("#validator").show();
        $("#proofdiv").hide();
        $("#check-text").html(procResult.processedText);
        return false;
    }

    validateText = _validateText;

    $("#cc-quit").click(function () {
        $("#validator").hide();
        $("#proofdiv").show();
    });

    $("#cc-remove").click(function () {
        // textArea has already been normalised
        textArea.value = processText(textArea.value, true).processedText;
        $("#validator").hide();
        $("#proofdiv").show();
    });
});

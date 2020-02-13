/*global $ goodChars charMatch standardInterface switchConfirm revertConfirm XRegExp */

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

$(function () {
    // to remove combining chars at line start which are not markable
    const linecheckRegex = XRegExp("^\\pM", "Amg");

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

    function validateText() {
        var text = textArea.value;
        text = text.normalize("NFC");
        text = text.replace(linecheckRegex, "");
        // replace the text with normalised version
        textArea.value = text;
        // convert any markup so does not get interpreted in the checker div
        text = htmlSafe(text);

        let procResult = processText(text, false);
        if(procResult.valid) {
            return true;
        }
        $("#checker").css("display", "flex");
        $("#proofdiv").hide();
        $("#check-text").html(procResult.processedText);
        return false;
    }

    // special handling for certain buttons
    // switch layout - validate before confirm
    $("#button4").click(function(event) {
        if(validateText() && confirm(switchConfirm)) {
            return;
        } else {
            event.preventDefault();
        }
    });

    // revert to original - validate before confirm
    $("#button8").click(function(event) {
        if(validateText() && confirm(revertConfirm)) {
            return;
        } else {
            event.preventDefault();
        }
    });

    // word check -- for standard interface:
    // Direct the (text-only) spellcheck doc to 'textframe'
    // (rather than 'proofframe', the statically defined target).
    $("#button10").click(function(event) {
        if(!validateText()) {
            event.preventDefault();
            return;
        }
        if(standardInterface) {
            document.getElementById('editform').target = 'textframe';
        }
    });

    $(".check_button").click(function(event) {
        if(!validateText()) {
            event.preventDefault();
        }
    });

    $("#cc-quit").click(function () {
        $("#checker").hide();
        $("#proofdiv").show();
    });

    $("#cc-remove").click(function () {
        // textArea has already been normalised
        textArea.value = processText(textArea.value, true).processedText;
        $("#checker").hide();
        $("#proofdiv").show();
    });

    var above = {
        "=": "\u0304", // macron
        ":": "\u0308", // diaeresis
        ".": "\u0307", // dot
        "`": "\u0300", // grave
        "'": "\u0301", // acute
        "^": "\u0302", // circumflex
        ")": "\u0306", // breve
        "~": "\u0303", // tilde
        ",": "\u0327", // cedilla
        "v": "\u030C", // caron
        "*": "\u030A", // ring
        "(": "\u0311", // inverted breve
    };

    var below = {
        "=": "\u0331", // macron
        ":": "\u0324", // diaeresis
        ".": "\u0323", // dot
        "`": "\u0316", // grave
        "'": "\u0317", // acute
        "^": "\u032D", // circumflex
        ")": "\u032E", // breve
        "~": "\u0330", // tilde
        ",": "\u0327", // cedilla
        "v": "\u032C", // caron
        "*": "\u0325", // ring
        "(": "\u032F", // inverted breve
    };

    var ligatures = {
        "ae": "\u00e6",
        "AE": "\u00c6",
        "oe": "\u0153",
        "OE": "\u0152",
    };

    $(textArea).on("input", function() {
        // Detect if the user has input one of the diacritical markups [..]
        // and convert it to a Unicode codepoint. If the codepoint is not
        // valid for the project, leave it as the markup. This also converts
        // a few common ligatures to their Unicode codepoints.

        // This event fires on every input into the textarea, but only
        // takes action if the last character entered is a ].
        var text = textArea.value;
        var endIndex = textArea.selectionStart;
        var char0Index, replaceChar;

        function maybeSubstitute() {
            // if replaceChar is good use it
            // this uses the local variables of the containing function
            if(goodChars.test(replaceChar)) {
                // replace markup with character and move caret back 4 places
                // and forward by length of replaceChar
                let newCaret = char0Index + replaceChar.length;
                textArea.value = text.slice(0, char0Index) + replaceChar + text.slice(endIndex);
                textArea.setSelectionRange(newCaret, newCaret);
            }
        }

        let char3Index = endIndex - 1;
        if(text[char3Index] != "]") {
            // if out of range would get ""
            return;
        }
        char0Index = endIndex - 4;
        if(text[char0Index] === "[") {
            let char1Index = endIndex - 3;
            replaceChar = ligatures[text.slice(char1Index, char3Index)];
            if(replaceChar) {
                maybeSubstitute();
                return;
            }
            let char1 = text[char1Index];
            let char2 = text[endIndex - 2];
            var code = above[char1];
            if(code) {
                replaceChar = char2 + code;
            } else {
                code = below[char2];
                if(code) {
                    replaceChar = char1 + code;
                } else {
                    return;
                }
            }
            replaceChar = replaceChar.normalize("NFC");
            maybeSubstitute();
        }
    });
});

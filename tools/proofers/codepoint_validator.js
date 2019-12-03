/*global $ codePoints */

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

    var badPattern = new RegExp("[^" + charClass + "]", "ug");
    var textArea = document.getElementById("text_data");

    $(".check_button").click(function(event) {
        var text = textArea.value;
        text = text.normalize("NFC");
        textArea.value = text;
        badPattern.lastIndex = 0;
        if(!badPattern.test(text)) {
            // no bad characters found
            return;
        }
        var replacement = "<span class='bad-char'>$&</span>";
        var markedText = htmlSafe(text).replace(badPattern , replacement);

        $("#checker").css("display", "flex");
        $("#proofdiv").hide();
        $("#check-text").html(markedText);
        event.preventDefault();
    });

    $("#cc-quit").click(function () {
        $("#checker").hide();
        $("#proofdiv").show();
    });

    $("#cc-remove").click(function () {
        textArea.value = textArea.value.replace(badPattern , "");
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
        var char0Index, char1Index, char3Index, replaceChar;

        function maybeSubstitute() {
            // if replaceChar does not contain any disallowed characters use it
            // this uses the local variables of the containing function
            badPattern.lastIndex = 0;
            if(!badPattern.test(replaceChar)) {
                // replace markup with character and move caret back 3 places
                textArea.value = text.slice(0, char0Index) + replaceChar + text.slice(endIndex);
                textArea.setSelectionRange(char1Index, char1Index);
            }
        }

        char3Index = endIndex - 1;
        if(text[char3Index] != "]") {
            // if out of range would get ""
            return;
        }
        char0Index = endIndex - 4;
        if(text[char0Index] === "[") {
            char1Index = endIndex - 3;
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

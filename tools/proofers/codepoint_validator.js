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

    var badClass = "[^" + charClass + "]";
    var badPattern = new RegExp(badClass, "ug");
    var badPattern0 = new RegExp(badClass, "u");

    var textArea = document.getElementById("text_data");

    $(".check_button").click(function(event) {
        var text = textArea.value;
        text = text.normalize("NFC");
        textArea.value = text;
        if(!badPattern0.test(text)) {
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

    function replaceMarkup() {
        // Detect if the text contains any diacritical markups [..]
        // and convert to Unicode codepoints. If the codepoint is not
        // valid for the project, leave it as the markup. This also converts
        // a few common ligatures to their Unicode codepoints.
        var text = textArea.value;
        var caretIndex = textArea.selectionStart;
        var replaceChar;
        var validMarkup = false;
        var displacement = 0;

        function replacer(match, chars, offset) {
            function maybeReplace() {
                if(!badPattern0.test(replaceChar)) {
                    validMarkup = true;
                    let caretOffset = caretIndex - offset;
                    if(caretOffset === 2) {
                        displacement += 1;
                    } else if(caretOffset === 3) {
                        displacement += 2;
                    } else if(caretOffset >= 4) {
                        displacement += 3;
                    }
                    return replaceChar;
                } else {
                    return match;
                }
            }

            replaceChar = ligatures[chars];
            if(replaceChar) {
                return maybeReplace();
            }
            let char1 = chars[0];
            let char2 = chars[1];
            var code = above[char1];
            if(code) {
                replaceChar = char2 + code;
            } else {
                code = below[char2];
                if(code) {
                    replaceChar = char1 + code;
                } else {
                    return match;
                }
            }
            replaceChar = replaceChar.normalize("NFC");
            return maybeReplace();
        }

        text = text.replace(/\[(..)\]/gu, replacer);
        if(validMarkup) {
            textArea.value = text;
            caretIndex -= displacement;
            textArea.setSelectionRange(caretIndex, caretIndex);
        }
    }

    // event fires on every input into the textarea
    $(textArea).on("input", replaceMarkup);

    // also replace on initial load
    replaceMarkup();
});

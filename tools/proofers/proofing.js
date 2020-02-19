/*global $ goodChars validateText standardInterface switchConfirm revertConfirm */

$(function () {
    var textArea = document.getElementById("text_data");

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

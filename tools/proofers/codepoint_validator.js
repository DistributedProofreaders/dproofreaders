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
    };

    $(textArea).on("input", function() {
        var text = textArea.value;
        var startPos = textArea.selectionStart;
        if(text[startPos - 1] != "]") {
            // if out of range would get ""
            return;
        }
        var begin = startPos - 4;
        if(text[begin] === "[") {
            let p1 = text[startPos - 3];
            let p2 = text[startPos - 2];
            var uchar;
            var code = above[p1];
            if(code) {
                uchar = p2 + code;
            } else {
                code = below[p2];
                if(code) {
                    uchar = p1 + code;
                } else {
                    return;
                }
            }
            uchar = uchar.normalize("NFC");
            // if not changed then original good and (possibly) bad combining diacritical code
            // will remain so need to test for bad chars
            badPattern.lastIndex = 0;
            if(!badPattern.test(uchar)) {
                textArea.value = text.slice(0, begin) + uchar + text.slice(startPos);
                begin += 1;
                textArea.setSelectionRange(begin, begin);
            }
        }
    });
});

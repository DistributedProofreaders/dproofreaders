/*global $ codePoints standardInterface switchConfirm revertConfirm */

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

    // For handling combining marks there is a regex unicode property escape
    // supported in Chrome and Safari but not in Firefox or Edge.
    // There is 3rd party http://xregexp.com/ but we need only one line:
    // the set of all codepoints with 'Mn' property, so include it here
    // this is the basic multilingual plane part

    var bmpCombiners = '\u0300-\u036F\u0483-\u0487\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0902\u093A\u093C\u0941-\u0948\u094D\u0951-\u0957\u0962\u0963\u0981\u09BC\u09C1-\u09C4\u09CD\u09E2\u09E3\u09FE\u0A01\u0A02\u0A3C\u0A41\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81\u0A82\u0ABC\u0AC1-\u0AC5\u0AC7\u0AC8\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01\u0B3C\u0B3F\u0B41-\u0B44\u0B4D\u0B56\u0B62\u0B63\u0B82\u0BC0\u0BCD\u0C00\u0C04\u0C3E-\u0C40\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81\u0CBC\u0CBF\u0CC6\u0CCC\u0CCD\u0CE2\u0CE3\u0D00\u0D01\u0D3B\u0D3C\u0D41-\u0D44\u0D4D\u0D62\u0D63\u0DCA\u0DD2-\u0DD4\u0DD6\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F71-\u0F7E\u0F80-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102D-\u1030\u1032-\u1037\u1039\u103A\u103D\u103E\u1058\u1059\u105E-\u1060\u1071-\u1074\u1082\u1085\u1086\u108D\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4\u17B5\u17B7-\u17BD\u17C6\u17C9-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u1922\u1927\u1928\u1932\u1939-\u193B\u1A17\u1A18\u1A1B\u1A56\u1A58-\u1A5E\u1A60\u1A62\u1A65-\u1A6C\u1A73-\u1A7C\u1A7F\u1AB0-\u1ABD\u1B00-\u1B03\u1B34\u1B36-\u1B3A\u1B3C\u1B42\u1B6B-\u1B73\u1B80\u1B81\u1BA2-\u1BA5\u1BA8\u1BA9\u1BAB-\u1BAD\u1BE6\u1BE8\u1BE9\u1BED\u1BEF-\u1BF1\u1C2C-\u1C33\u1C36\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE0\u1CE2-\u1CE8\u1CED\u1CF4\u1CF8\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20DC\u20E1\u20E5-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302D\u3099\u309A\uA66F\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA825\uA826\uA8C4\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA951\uA980-\uA982\uA9B3\uA9B6-\uA9B9\uA9BC\uA9E5\uAA29-\uAA2E\uAA31\uAA32\uAA35\uAA36\uAA43\uAA4C\uAA7C\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEC\uAAED\uAAF6\uABE5\uABE8\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F';

    // In checking glyphs with a combining mark there will be only a few valid
    // combinations. First find candidate pairs, then check if valid.
    // This version checks only one combining mark but could be extended.
    // Multiple marks and marks at start of lines are removed first.
    // Do two passes: first single codepoints, then combining pairs.
    // If marking or removing, first pass must let through all combining marks
    // and second pass find all pairs (character + mark).
    // If only testing for bad characters, the first pass can allow through
    // only valid combining marks and second pass can find only pairs with
    // these marks before validating.
    // If testing a glyph 2nd pass can just test for pairs of codepoints.

    // With unicode escapes we can use \p{Mn} instead of bmpCombiners

    var processBadPattern = new RegExp("[^" + bmpCombiners + charClass + "]", "ug");

    // any char followed by a combining char
    var ProcessCombiningPattern = new RegExp(".[" + bmpCombiners + "]", "ug");

    // validCombinations & validCombiners are constructed from project data not
    // yet implemented: the following would allow F,H with breve and M grave
    // validCombinations = new RegExp("^$|[\u0046\u0048]\u0306|\u004d\u0300", "u");
    // validCombiners = "\u0306\u0300";
    // ^$ ensures this will not match anything
    var validCombinations = new RegExp("^$", "u");
    var validCombiners = "";

    var testBadPattern = new RegExp("[^" + validCombiners + charClass + "]", "u");

    var textArea = document.getElementById("text_data");

    // mark (or remove if in 'clean' mode) bad glyphs, return text and status
    function processText(text, clean) {
        var bad = false;

        function codepointReplacer(match) {
            if(clean) {
                return "";
            }
            bad = true;
            return "<span class='bad-char'>" + match + "</span>";
        }

        function combiningReplacer(match) {
            if(validCombinations.test(match)) {
                return match;
            } else if(clean) {
                return "";
            }
            bad = true;
            return "<span class='bad-char'>" + match + "</span>";
        }

        text = text.replace(processBadPattern, codepointReplacer);
        text = text.replace(ProcessCombiningPattern, combiningReplacer);
        return {processedText: text, valid: !bad};
    }

    // this tests a single glyph - one or two codepoints
    function testText(text) {
        if(testBadPattern.test(text)) {
            return false;
        }
        if(/../.test(text) && !validCombinations.test(text)) {
            return false;
        }
        return true;
    }

    // to remove combining chars at line starts and multiple combining chars
    var linecheckRegex = new RegExp("^[" + bmpCombiners + "]|[" + bmpCombiners + "]{2,}", "mg");

    // check the text, if bad glyph, show marked text in the 'checker' div
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
        var char0Index, char1Index, char3Index, replaceChar;

        function maybeSubstitute() {
            // if replaceChar is valid use it
            // this uses the local variables of the containing function
            if(testText(replaceChar)) {
                // replace markup with character and move caret back 3 places
                // some browsers may place the caret incorrectly if the result
                // contains a combining mark.
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

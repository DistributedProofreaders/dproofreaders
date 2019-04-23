/*global $ lineWrap */

// whenever textarea is changed ensure all \n are preceded by the symbol.
// find all \n not preceded by symbol. look behind doesn't work so use
// look-ahead. In case \n was deleted find and delete symbols not followed by \n
// If we do any replacements caret goes to end so save and restore it.
// need also to adjust caret position if adding or removing symbols.
// It is desirable for a sequence of keys to give same effect as before:
// 'End'-'back delete' should delete last charcter on line, so move caret to
// left of symbol when it is at end of line by acting on keyup and click.
// 'End'-'Delete' should delete the \n. Since we have moved the caret this will
// at first delete the symbol
// We can use deleting the symbol to delete a newline. In this case the caret
// will precede \n. If insering \n the caret will follow it.
// doesn't work correctly in Internet explorer 9 or chrome 16

$(function () {
    "use strict";
    if (0 === lineWrap) {
        return;
    }
    var msie = document.documentMode;
    if (msie < 10) {
        return;
    }
    var symbol = "\u21a9";
    var text1 = $("#text_data");
    var reSymb = new RegExp(symbol, "g");
    var reAdd = new RegExp("(?:[^" + symbol + "]|^)(?=\n)", "g");
    var reRem = new RegExp(symbol + "(?!\n)", "g");
    var caretPos;
    var restoreCaret;

    function addSymbol(match) {
        caretPos += 1;
        restoreCaret = true;
        return match + symbol;
    }

    function removeSymbol() {
        // otherwise will jump forward
        caretPos -= 1;
        restoreCaret = true;
        return "";
    }

    function setCaret(pos) {
        text1[0].setSelectionRange(pos, pos);
    }

    function adjustSymbol() {
        caretPos = text1[0].selectionStart;
        restoreCaret = false;
        var txt = text1.val();
        var code = txt.charCodeAt(caretPos);
        if (10 === code) {
            // remove it
            txt = txt.slice(0, caretPos) + txt.slice(caretPos + 1);
            restoreCaret = true;
        }
        txt = txt.replace(reAdd, addSymbol);
        text1.val(txt.replace(reRem, removeSymbol));
        if (restoreCaret) {
            setCaret(caretPos);
        }
    }

    function removeAllSymbols(txt) {
        return txt.replace(reSymb, "");
    }

    text1.click(function () {
        // if at end of line move to left of symbol
        var cPos = text1[0].selectionStart;
        if (10 === text1.val().charCodeAt(cPos)) {
            setCaret(cPos - 1);
        }
    });

    text1.keyup(function (event) {
        // if at end of line move to left of symbol except
        // if fwd arrow move to start of next line
        var cPos = text1[0].selectionStart;
        if (10 === text1.val().charCodeAt(cPos)) {
            if (event.which === 39) { // fwd arrow
                setCaret(cPos + 1);
            } else {
                setCaret(cPos - 1);
            }
        }
    });

    text1.on("input", adjustSymbol);

    // on load, insert symbols before \n
    text1.val(text1.val().replace(/\n/g, symbol + "\n"));

    // remove symbols before saving
    $("#editform").submit(function () {
        text1.val(removeAllSymbols(text1.val()));
    });

    text1.on("copy", function (ev) {
        var selection = text1.val().slice(text1[0].selectionStart, text1[0].selectionEnd);
        ev.originalEvent.clipboardData.setData("text/plain", removeAllSymbols(selection));
        ev.originalEvent.preventDefault();
    });

    text1.on("dragstart", function (ev) {
        var selection = text1.val().slice(text1[0].selectionStart, text1[0].selectionEnd);
        ev.originalEvent.dataTransfer.setData("text/plain", removeAllSymbols(selection));
    });
});

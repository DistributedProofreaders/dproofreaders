/*global $ document keyTitles top encodeWord apiUrl */

$(function () {
    "use strict";

    var charSelector = $("#char-selector");
    var largeChar = document.getElementById("large_char");

    function enableBoard(newCode) {
        // hide the visible key block
        $(".show", charSelector).removeClass("show");
        // show the new one
        $("._" + newCode, charSelector).addClass("show");
        // mark the new selected tab
        $(".selected-tab", charSelector).removeClass("selected-tab");
        $("#id_" + newCode, charSelector).addClass("selected-tab");
        largeChar.value = ""; // remove old character
        top.focusText();
    }

    function loadKb(data) {
        var kbData = data.pickers;
        var initialSet = false;
        var initialCode;
        var rowString = "";
        var selectorString = "<div id='selector_row'>";
        function drawRow(charRow) {
            rowString += "<div>";
            var chars = charRow.split("");
            // this will fail for surrogate pairs
            // when utf8 implemented use following instead, doesn't work in IE
            // see https://stackoverflow.com/questions/4547609/how-do-you-get-a-string-to-a-character-array-in-javascript/34717402#34717402
            // var chars = charRow.split(/(?=.)/u);
            chars.forEach(function (character) {
                if (" " === character) {
                    rowString += "<button type='button' class='picker invisible'></button>";
                } else {
                    rowString += "<button type='button' class='picker'>" + character + "</button>";
                }
            });
            rowString += "</div>\n";
        }
        kbData.forEach(function (item) {
            var code = item.code;
            var safeCode = encodeWord(code);
            if (!initialSet) {
                initialSet = true;
                initialCode = safeCode;
            }
            selectorString += "<button type='button' id='id_" + safeCode + "' class='selector_button'>" + code + "</button>";
            rowString += "<div class='_" + safeCode + " key-block'>";
            drawRow(item.upper);
            drawRow(item.lower);
            rowString += "</div>\n";
        });
        selectorString += "</div>\n";
        charSelector.html(selectorString + rowString);
        enableBoard(initialCode);
        $(".selector_button", charSelector).click(function () {
            enableBoard(this.id.slice(3));
        });

        $(".picker", charSelector).click(function () {
            top.insertCharacter(this.innerText);
        }).mouseover(function () {
            largeChar.value = this.innerText;
        }).each(function () {
            if (keyTitles.hasOwnProperty(this.innerText)) {
                this.title = keyTitles[this.innerText];
            }
        });
    }

    $.getJSON(apiUrl, {"q": "v1/pickers"}, loadKb);
});

/*global $ document top */

$(function () {
    "use strict";

    var charSelector = $("#char-selector");
    var largeChar = document.getElementById("large_char");

    function enableBoard(newCode) {
        // hide the visible key block
        $(".show", charSelector).removeClass("show");
        // show the new one
        $("." + newCode, charSelector).addClass("show");
        // mark the new selected tab
        $(".selected-tab", charSelector).removeClass("selected-tab");
        $("#" + newCode, charSelector).addClass("selected-tab");
        largeChar.value = ""; // remove old character
        top.focusText();
    }

    // find the code of the first selector button
    var initialCode = $("#selector_row > button")[0].id;
    enableBoard(initialCode);

    $(".selector_button", charSelector).click(function () {
        enableBoard(this.id);
    });

    $(".picker", charSelector).click(function () {
        top.insertCharacter(this.textContent);
    }).mouseover(function () {
        largeChar.value = this.textContent;
    });
});

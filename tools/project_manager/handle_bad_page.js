/*global $ validateText */

$(function () {
    $("#update_text").click(function(event) {
        if(!validateText()) {
            event.preventDefault();
        }
    });
});

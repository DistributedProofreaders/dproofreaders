/* global $ fallbackFont */

$(function() {
    $("#fntFace").change(function() {
        top.changeFontFamily(this.options[this.selectedIndex].value, this.options[this.selectedIndex].text, fallbackFont);
    });
});

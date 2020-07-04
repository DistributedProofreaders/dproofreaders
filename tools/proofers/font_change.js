/* global $ fontFaces */

$(function() {
    $("#fntFace").change(function() {
        $("#text_data").css("font-family", fontFaces[this.value]);
    });
});

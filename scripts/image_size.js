/*global $ */
$(function () {
    "use strict";
    var image = $("#image");
    var percent = localStorage.getItem("image_percent");
    if(!percent) {
        percent = 100;
    }
    $("#percent").val(percent);

    function setZoom() {
        image.width(10 * percent);
        image.height("auto");
    }

    $("#resize").click(function () {
        percent = $("#percent").val();
        localStorage.setItem("image_percent", percent);
        setZoom();
    });

    setZoom();
});

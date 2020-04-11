/*global $ */
/* exported openToolboxPopup */

var openedToolboxPopups = [];

$(function () {
    "use strict";

    function showPicker() {
        $("#char-picker").show();
        $("#show-picker").hide();
    }

    function hidePicker() {
        $("#char-picker").hide();
        $("#show-picker").show();
    }

    $("#hide-picker").click(function () {
        hidePicker();
        localStorage.setItem("picker_data", "hide");
    });

    $("#show-picker").click(function () {
        showPicker();
        localStorage.setItem("picker_data", "show");
    });

    var pickerState = localStorage.getItem("picker_data");
    if (pickerState && (pickerState === "hide")) {
        hidePicker();
    }

    $(window).on("beforeunload", function () {
        openedToolboxPopups.forEach(function (openedToolboxPopup) {
            openedToolboxPopup.close();
        });
    });
});

function openToolboxPopup(url, width, height, name) {
    var features = "width=" + width + ", height=" + height + ", directories=0, location=0, menubar=0, resizable, scrollbars, status=0, toolbar=0";
    openedToolboxPopups.push(window.open(url, name, features));

    return false;
}

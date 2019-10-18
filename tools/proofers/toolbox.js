/*global $ localStorage */

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
});

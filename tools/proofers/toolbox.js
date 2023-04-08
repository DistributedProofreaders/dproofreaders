/*global */
/* exported openToolboxPopup */

var openedToolboxPopups = [];

window.addEventListener('DOMContentLoaded', function() {
    "use strict";

    function showPicker() {
        document.getElementById("char-picker").style.display = 'inherit';
        document.getElementById("show-picker").classList.add("nodisp");
    }

    function hidePicker() {
        document.getElementById("char-picker").style.display = 'none';
        document.getElementById("show-picker").classList.remove("nodisp");
    }

    document.getElementById("hide-picker").addEventListener("click", function () {
        hidePicker();
        localStorage.setItem("picker_data", "hide");
    });

    document.getElementById("show-picker").addEventListener("click", function () {
        showPicker();
        localStorage.setItem("picker_data", "show");
    });

    var pickerState = localStorage.getItem("picker_data");
    if (pickerState && (pickerState === "hide")) {
        hidePicker();
    }

    this.window.addEventListener("beforeunload", function () {
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

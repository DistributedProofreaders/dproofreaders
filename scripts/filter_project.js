/* global */
window.addEventListener('DOMContentLoaded', function() {
    "use strict";
    var langSelector = document.getElementById("language");
    var langMatch = document.getElementById("lang-match");
    var diffAll = document.getElementById("diff-all");
    var diffOpt = document.querySelectorAll(".diff-opt");

    function showMatcher() {
        langMatch.style.display = 0 === langSelector.selectedIndex ? "none" : "block";
    }

    langSelector.addEventListener("change", function () {
        showMatcher();
    });

    diffAll.addEventListener("change", function () {
        diffOpt.forEach(diffOption => {
            diffOption.checked = false;
        });
    });

    diffOpt.forEach(diffOption => {
        diffOption.addEventListener("change", function() {
            diffAll.checked = false;
        });
    });

    showMatcher();
});

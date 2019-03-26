/*global $*/
$(function () {
    "use strict";
    var langSelector = $("#language");
    var langMatch = $("#lang-match");
    var diffAll = $("#diff-all");
    var diffOpt = $(".diff-opt");

    function showMatcher() {
        langMatch.css("visibility", (0 === langSelector.prop("selectedIndex") ? "hidden" : "visible"));
    }

    langSelector.change(function () {
        showMatcher();
    });

    diffAll.change(function () {
        diffOpt.prop("checked", false);
    });

    diffOpt.change(function () {
        diffAll.prop("checked", false);
    });

    showMatcher();
});

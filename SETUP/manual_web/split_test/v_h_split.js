/* global $ splitControl */
$(function () {
    let reDraw = $.Callbacks();

    $(window).resize(function () {
        reDraw.fire();
    });

    let mainSplit = splitControl("#top-container", reDraw, {splitDirection: "vertical", dragBarColor: "green"});

    splitControl("#sub-container", mainSplit.reSize, {splitDirection: "horizontal", dragBarSize: 10, dragBarColor: "blue"});

    mainSplit.reLayout();
});


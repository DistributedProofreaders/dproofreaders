/* global $ splitControl */
$(function () {
    let reDraw = $.Callbacks();

    $(window).resize(function () {
        reDraw.fire();
    });

    let splitter = splitControl();
    let mainSplit = splitter.setup("#top-container", reDraw, {dragBarColor: "green"});

    splitter.setup("#sub-container", mainSplit.reSize, {splitDirection: splitter.DIRECTION.HORIZONTAL, dragBarSize: 10, dragBarColor: "blue"});

    mainSplit.reLayout();
});


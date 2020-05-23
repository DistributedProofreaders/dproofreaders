/* global $ splitControl */
$(function () {
    let reDraw = $.Callbacks();

    $(window).resize(function () {
        reDraw.fire();
    });

    let mainSplit = splitControl("#container", reDraw, {splitDirection: "horizontal"});
    mainSplit.reLayout();
});


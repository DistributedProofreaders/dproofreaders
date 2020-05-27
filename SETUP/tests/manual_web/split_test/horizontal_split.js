/* global $ splitControl */
$(function () {
    let reDraw = $.Callbacks();

    $(window).resize(function () {
        reDraw.fire();
    });

    let splitter = splitControl();
    let mainSplit = splitter.setup("#container", reDraw, {splitDirection: splitter.DIRECTION.HORIZONTAL});
    mainSplit.reLayout();
});


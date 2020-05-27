/* global $ splitControl */
$(function () {
    let splitter = splitControl();
    let mainSplit = splitter.setup("#container", {splitDirection: splitter.DIRECTION.HORIZONTAL});
    mainSplit.reLayout();
});


/* global $ splitControl */
$(function () {
    let splitter = splitControl();
    let mainSplit = splitter.setup("#top-container", {dragBarColor: "green"});
    splitter.setup("#sub-container", {splitDirection: splitter.DIRECTION.HORIZONTAL, reDraw: mainSplit.reSize, dragBarSize: 10, dragBarColor: "blue"});
    mainSplit.reLayout();
});

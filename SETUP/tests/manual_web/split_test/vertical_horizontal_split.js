/* global $ splitControl */
$(function () {
    let mainSplit = splitControl("#top-container", {dragBarColor: "green"});
    splitControl("#sub-container", {splitVertical: false, reDraw: mainSplit.onResize, dragBarSize: 10, dragBarColor: "blue"});
    mainSplit.reLayout();
});

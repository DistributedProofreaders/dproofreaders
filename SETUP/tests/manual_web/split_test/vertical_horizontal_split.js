/* global $ splitControl */
$(function () {
    let mainSplit = splitControl("#top-container");
    splitControl("#sub-container", {splitVertical: false, reDraw: mainSplit.reSize, dragBarSize: 10});
    mainSplit.reLayout();
});

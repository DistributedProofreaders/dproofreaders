/* global $ splitControl */
$(function () {
    let mainSplit = splitControl("#top-container", {dragBarColor: "green"});
    let subSplit = splitControl("#sub-container", {splitVertical: false, dragBarSize: 10, dragBarColor: "blue"});
    mainSplit.onResize.add(subSplit.reLayout);
    window.addEventListener("resize", mainSplit.reLayout);
    mainSplit.reLayout();
});

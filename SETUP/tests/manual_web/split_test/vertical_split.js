/* global $ splitControl */
$(function () {
    let mainSplit = splitControl("#container");
    window.addEventListener("resize", mainSplit.reLayout);
    mainSplit.reLayout();
});

/* global $ splitControl viewData */
$(function () {
    let mainSplit = splitControl("#container", {splitVertical: viewData.splitVertical, splitPercent: viewData.splitPercent});
    mainSplit.reLayout();
});

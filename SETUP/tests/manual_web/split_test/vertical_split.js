/* global $ splitControl */
$(function () {
    let splitter = splitControl();
    let mainSplit = splitter.setup("#container");
    mainSplit.reLayout();
});

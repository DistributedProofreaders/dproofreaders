/* global splitControl */
window.addEventListener("DOMContentLoaded", function () {
    let mainSplit = splitControl(document.getElementById("container"));
    window.addEventListener("resize", mainSplit.reLayout);
    mainSplit.reLayout();
});

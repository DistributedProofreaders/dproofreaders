/* global splitControl */
window.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("container");
    let mainSplit = splitControl(container);
    window.addEventListener("resize", mainSplit.reLayout);
    mainSplit.reLayout();
});

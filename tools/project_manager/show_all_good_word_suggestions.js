/*global splitControl */

window.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("suggestions_container");
    let mainSplit = splitControl(container, {
        splitVertical: true,
        splitPercent: 40,
    });
    window.addEventListener("resize", mainSplit.reLayout);
    mainSplit.reLayout();
});

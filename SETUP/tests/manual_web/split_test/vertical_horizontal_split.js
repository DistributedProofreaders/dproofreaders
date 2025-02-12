/* global splitControl */
window.addEventListener("DOMContentLoaded", function () {
    let mainSplit = splitControl(document.getElementById("top-container"), { dragBarColor: "green" });
    let subSplit = splitControl(document.getElementById("sub-container"), { splitVertical: false, dragBarSize: 10, dragBarColor: "blue" });
    mainSplit.onResize.add(subSplit.reLayout);
    window.addEventListener("resize", mainSplit.reLayout);
    mainSplit.reLayout();
});

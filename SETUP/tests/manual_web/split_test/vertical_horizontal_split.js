/* global splitControl */
window.addEventListener("DOMContentLoaded", function () {
    const topContainer = document.getElementById("top-container");
    const subContainer = document.getElementById("sub-container");
    let mainSplit = splitControl(topContainer, { dragBarColor: "green" });
    let subSplit = splitControl(subContainer, { splitVertical: false, dragBarSize: 10, dragBarColor: "blue" });
    mainSplit.onResize.add(subSplit.reLayout);
    window.addEventListener("resize", mainSplit.reLayout);
    mainSplit.reLayout();
});

/* global splitControl */
window.addEventListener("load", () => {
    let mainSplit = splitControl("#top-container", {dragBarColor: "green"});
    splitControl("#sub-container", {splitVertical: false, reDraw: mainSplit.reSize, dragBarSize: 10, dragBarColor: "blue"});
    mainSplit.reLayout();
});

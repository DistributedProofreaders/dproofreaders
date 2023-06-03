/*global splitControl */

window.addEventListener('DOMContentLoaded', function() {
    let mainSplit = splitControl("#suggestions_container", {
        splitVertical: true,
        splitPercent: 40,
    });
    window.addEventListener("resize", mainSplit.reLayout);
    mainSplit.reLayout();
});

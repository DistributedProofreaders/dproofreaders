import { splitControl } from "../../scripts/splitControl.js";

window.addEventListener("DOMContentLoaded", function () {
    let mainSplit = splitControl(document.getElementById("suggestions_container"), {
        splitVertical: true,
        splitPercent: 40,
    });
    window.addEventListener("resize", mainSplit.reLayout);
    mainSplit.reLayout();
});

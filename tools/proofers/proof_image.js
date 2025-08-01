/*global $ imageData */
import { makeImageWidget } from "../../scripts/control_bar.js";

window.addEventListener("DOMContentLoaded", function () {
    let imageDiv = $("#image-view");

    let imageWidget = makeImageWidget(imageDiv, imageData.align);
    imageWidget.setup(imageData.storageKey);
    imageWidget.setImage(imageData.imageUrl);
    window.addEventListener("resize", imageWidget.reScroll);
});

/*global $ makeImageWidget imageData */

$(function () {
    let imageDiv = $("#image-view");

    let imageWidget = makeImageWidget(imageDiv, imageData.storageKey);
    imageWidget.setImage(imageData.imageUrl);
});

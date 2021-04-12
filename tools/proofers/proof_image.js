/*global $ makeImageWidget imageData */

$(function () {
    let imageDiv = $("#image-view");

    let imageWidget = makeImageWidget(imageDiv, imageData.storageKey, imageData.align);
    imageWidget.setImage(imageData.imageUrl);
});

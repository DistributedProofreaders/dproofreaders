/*global $ makeImageWidget imageData */

window.addEventListener('DOMContentLoaded', function() {
    let imageDiv = $("#image-view");

    let imageWidget = makeImageWidget(imageDiv, imageData.align);
    imageWidget.setup(imageData.storageKey);
    imageWidget.setImage(imageData.imageUrl);
});

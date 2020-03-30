/*global $ imageUrl changeWatcher */
$(function () {

    var selector = document.getElementById("page-select");

    function showImage() {
        $("#image").attr("src", imageUrl + selector.value);
    }

    changeWatcher.newPage.add(showImage);

    showImage();
});

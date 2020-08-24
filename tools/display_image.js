/*global $ pageBrowserData imageControl hiddenProject pageChanger */

$(function () {
    let topDiv = $("#top-div");
    // the non-scrolling area which will contain any error message and the controls
    let fixHead = $("<div>", {class: 'fixed-box control-form'});
    topDiv.append(fixHead);

    if(pageBrowserData.errorMessage) {
        fixHead.append($("<p>", {class: 'error'}).append(pageBrowserData.errorMessage));
    }
    let stretchDiv = $("<div>", {class: 'stretch-box'});
    topDiv.append(stretchDiv);

    let theImageControl = imageControl();
    let pageControlForm = $("<form>", {method: "get", class: "inline"});
    pageControlForm.append(hiddenProject(), pageChanger(pageControlForm));
    fixHead.append(theImageControl.controls, pageControlForm);
    stretchDiv.addClass("overflow-auto image-back").append(theImageControl.image);
    theImageControl.setZoom();
});

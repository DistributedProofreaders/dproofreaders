/*global $ pageBrowserData splitter imageControl textControl pageChanger roundSelect hiddenProject initSplit */

$(function () {
    let topDiv = $("#top-div");
    let fixHead = $("<div>", {class: 'fix control-form'});
    let stretchDiv = $("<div>", {class: 'stretch'});

    topDiv.append(fixHead, stretchDiv);

    let pageControlForm = $("<form>", {method: "get"});

    fixHead.append(pageBrowserData.heading, pageControlForm);

    let theSplitter = splitter(stretchDiv);
    let theImageControl = imageControl();
    let theTextControl = textControl();

    pageControlForm.append(hiddenProject(), theImageControl.controls, pageChanger(), roundSelect(),
        theSplitter.buttons, theTextControl.controls);

    theSplitter.mainSplit.pane1.addClass('overflow-auto image-back').append(theImageControl.image);
    theImageControl.setZoom();

    let theSubSplit = initSplit(theSplitter.mainSplit.pane2, theSplitter.mainSplit.reSize, {splitDirection: "horizontal", splitPercent: 50, minSiz0: 30, minSiz1: 2});

    theSubSplit.pane1.append(theTextControl.textArea);

    // re-layout after drawing fixed div
    theSplitter.mainSplit.reLayout();
});

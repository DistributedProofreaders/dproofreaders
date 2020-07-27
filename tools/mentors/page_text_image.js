/* global $ pageBrowserData topLine viewSplitter imageControl textControl pageChanger splitControl
roundSelect proofIntData projectInput projectSelectButton */

$(function () {
    let topDiv = $("#top-div");
    // the non-scrolling area which will contain any error messagfe and the form with controls
    let fixHead = $("<div>", {class: 'fixed-box control-form'});
    let pageControlForm = $("<form>", {method: "get"});
    topDiv.append(fixHead);

    if(pageBrowserData.errorMessage) {
        fixHead.append($("<p>", {class: 'error'}).append(pageBrowserData.errorMessage));
    }
    fixHead.append(pageControlForm);
    if(!pageBrowserData.projectid) {
        // just show the project input
        pageControlForm.append($("<p>").append(proofIntData.strings.selectAProject), projectInput(), projectSelectButton());
    } else if(!pageBrowserData.currentPage) {
        // show project name, and page selector
        pageControlForm.append(topLine(), pageChanger(), " ", roundSelect(false));
    } else {
        // if a page is given show it in scrollable area
        let stretchDiv = $("<div>", {class: 'stretch-box'});
        topDiv.append(stretchDiv);

        let theImageControl = imageControl();
        let imageDiv = $("<div>", {class: 'overflow-auto image-back'}).append(theImageControl.image);
        theImageControl.setZoom();

        let theTextControl = textControl();
        let topTextDiv = $("<div>").append(theTextControl.textArea);
        let blankDiv = $("<div>");
        let textDiv = $("<div>").append(topTextDiv, blankDiv);

        stretchDiv.append(imageDiv, textDiv);
        let theSplitter = viewSplitter(stretchDiv);

        pageControlForm.append(topLine(), pageChanger(), " ", roundSelect(true), " ", theImageControl.controls,
            theSplitter.buttons, theTextControl.controls);

        let subSplitter = splitControl();
        subSplitter.setup(textDiv, {splitDirection: subSplitter.DIRECTION.HORIZONTAL, reDraw: theSplitter.mainSplit.reSize});

        theSplitter.mainSplit.reLayout();
    }
});
/* global $ pageBrowserData viewSplitter imageControl textControl pageChanger splitControl
hiddenProject projectReset roundSelect proofIntData projectInput projectSelectButton */

$(function () {
    let topDiv = $("#top-div");
    // the non-scrolling area which will contain any error messagfe and the form with controls
    let fixHead = $("<div>", {class: 'fixed-box control-form'});
    let pageControlForm = $("<form>", {method: "get"});
    topDiv.append(fixHead);

    if(pageBrowserData.errorMessage) {
        fixHead.append($("<p>", {class: 'error'}).append(pageBrowserData.errorMessage));
    }
    if(!pageBrowserData.projectid) {
        // just show the project input
        fixHead.append($("<p>").append(proofIntData.strings.selectAProject), pageControlForm);
        pageControlForm.append(projectInput(), projectSelectButton());
    } else {
        // show project name
        fixHead.append($("<p>").append(pageBrowserData.heading), pageControlForm);
        if(!pageBrowserData.currentPage) {
        // show page selector
            pageControlForm.append(hiddenProject(), pageChanger(), roundSelect(false));
        } else {
            // if a page is given show it in scrollable area with controls
            // and a button to change project.
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

            pageControlForm.append(projectReset(), pageChanger(), roundSelect(true));
            fixHead.append(theImageControl.controls, theSplitter.buttons, theTextControl.controls);

            let subSplitter = splitControl();
            subSplitter.setup(textDiv, {splitDirection: subSplitter.DIRECTION.HORIZONTAL, reDraw: theSplitter.mainSplit.reSize});

            theSplitter.mainSplit.reLayout();
        }
    }
});

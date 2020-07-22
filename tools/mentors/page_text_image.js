/* global $ pageBrowserData topLine viewSplitter imageControl textControl pageChanger splitControl
roundSelect proofIntData projectInput pageInput projectSelectButton */

$(function () {
    let topDiv = $("#top-div");
    let fixHead = $("<div>", {class: 'fix control-form'});
    let pageControlForm = $("<form>", {method: "get"});
    topDiv.append(fixHead);

    if(pageBrowserData.projectid && !pageBrowserData.errorMessage) {
        let stretchDiv = $("<div>", {class: 'stretch'});
        topDiv.append(stretchDiv);
        fixHead.append(pageControlForm);
        pageControlForm.append(topLine());

        let imageDiv = $("<div>");
        let textDiv = $("<div>");
        stretchDiv.append(imageDiv, textDiv);

        let theSplitter = viewSplitter(stretchDiv);
        let theImageControl = imageControl();
        let theTextControl = textControl();

        pageControlForm.append(theImageControl.controls, " ", pageChanger(), " ", roundSelect(),
            theSplitter.buttons, theTextControl.controls);

        imageDiv.addClass('overflow-auto image-back').append(theImageControl.image);
        theImageControl.setZoom();

        let topTextDiv = $("<div>").append(theTextControl.textArea);
        let blankDiv = $("<div>");
        textDiv.append(topTextDiv, blankDiv);

        let splitter = splitControl();
        splitter.setup(textDiv, {splitDirection: splitter.DIRECTION.HORIZONTAL, reDraw: theSplitter.mainSplit.reSize});

        theSplitter.mainSplit.reLayout();
    } else {
        if(pageBrowserData.errorMessage) {
            fixHead.append("<p class='error'>" + pageBrowserData.errorMessage + "</p>");
        }
        fixHead.append("<p>" + proofIntData.strings.selectAProject + "</p>", pageControlForm);
        pageControlForm.append(projectInput(), pageInput(), roundSelect(false), projectSelectButton());
    }
});

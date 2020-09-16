/*global $ mode pageBrowserData proofIntData imageControl pageChanger viewSplitter textControl
hiddenProject projectReset roundSelect projectSelector pageTitle mentorMode */

$(function () {
    // Construct the hidden modeInput to persist the mode
    // and appropriate buttons to switch to other modes
    var hiddenMode = function() {
        return $("<input>", {type: 'hidden', name: 'mode', value: mode});
    };

    var modeControl = function() {
        let modeObjs = [
            {type: 'image', caption: proofIntData.strings.showImage},
            {type: 'text', caption: proofIntData.strings.showText},
            {type: 'imageText', caption: proofIntData.strings.showImageText}
        ];

        let modeInput = hiddenMode();
        let returnValue = [modeInput];

        modeObjs.forEach(function (modeObj) {
            if(modeObj.type !== mode) {
                let modeButton = $("<input>", {type: 'submit', value: modeObj.caption});
                modeButton.click(function () {
                    modeInput.val(modeObj.type);
                });
                returnValue.push(modeButton);
            }
        });
        return returnValue;
    };

    let topDiv = $("#top-div");
    // the non-scrolling area which will contain any error message and the form with controls
    let fixHead = $("<div>", {class: 'fixed-box control-form'});
    topDiv.append(fixHead);

    if(pageBrowserData.errorMessage) {
        fixHead.append($("<p>", {class: 'error'}).append(pageBrowserData.errorMessage));
    }

    let pageControlForm = $("<form>", {method: "get"});
    if(!pageBrowserData.projectid) {
        // just show the project input
        fixHead.append(pageControlForm);
        pageControlForm.append(projectSelector(), hiddenMode());
    } else {
        // show project name
        fixHead.append($("<p>").append(pageBrowserData.heading), pageControlForm);
        if(!pageBrowserData.currentPage) {
            // show page selector
            pageControlForm.append(hiddenProject(), hiddenMode(), pageChanger(pageControlForm));
            if(mode !== "image") {
                // round selector not submitting if changed
                pageControlForm.append(" ", roundSelect(false));
            }
        } else {
            // if a page is given show its name in title and display it in
            // scrollable area with controls and a button to change project.
            pageTitle();
            pageControlForm.append(projectReset(), modeControl(), pageChanger(pageControlForm));
            let stretchDiv = $("<div>", {class: 'stretch-box'});
            topDiv.append(stretchDiv);

            switch (mode) {
            // extra { } to use block-scoped variables here
            case "image": {
                let theImageControl = imageControl();
                fixHead.append(theImageControl.controls);
                stretchDiv.addClass("overflow-auto image-back").append(theImageControl.image);
                theImageControl.setZoom();
                break;
            }
            case "text": {
                let theTextControl = textControl();
                // round selector submits if changed
                pageControlForm.append(roundSelect(true));
                fixHead.append(theTextControl.controls);
                theTextControl.textArea.prop("readonly", !mentorMode);
                stretchDiv.append(theTextControl.textArea);
                break;
            }
            case "imageText": {
                let theImageControl = imageControl();
                let theTextControl = textControl();
                theTextControl.textArea.prop("readonly", !mentorMode);
                let imageDiv = $("<div>", {class: 'overflow-auto image-back'}).append(theImageControl.image);
                let textDiv = $("<div>").append(theTextControl.textArea);
                if(mentorMode) {
                    let topTextDiv = textDiv;
                    let bottomTextDiv = $("<div>", {class: 'image-back'});
                    textDiv = $("<div>").append(topTextDiv, bottomTextDiv);
                }
                stretchDiv.append(imageDiv, textDiv);
                let theSplitter = viewSplitter(stretchDiv);
                if(mentorMode) {
                    const subSplitID = "sub_split_percent";
                    let subSplitPercent = localStorage.getItem(subSplitID);
                    if(!subSplitPercent) {
                        // in local storage 0 is stored as "0" and evaluates to true
                        subSplitPercent = 100;
                    }

                    let subSplitter = splitControl(textDiv, {splitVertical: false, splitPercent: subSplitPercent, reDraw: theSplitter.mainSplit.reSize});
                    subSplitter.dragEnd.add(function (percent) {
                        localStorage.setItem(subSplitID, percent);
                    });
                }
                pageControlForm.append(roundSelect(true));
                fixHead.append(theImageControl.controls, theSplitter.buttons, theTextControl.controls);
                theImageControl.setZoom();
                // re-layout after drawing fixed div
                theSplitter.mainSplit.reLayout();
                break;
            }
            }
        }
    }
});

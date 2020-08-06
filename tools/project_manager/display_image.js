/*global $ mode pageBrowserData proofIntData imageControl pageChanger viewSplitter textControl
hiddenProject projectReset roundSelect projectSelector */

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
        pageControlForm.append(projectSelector(), hiddenMode());
    } else {
        // show project name
        fixHead.append($("<p>").append(pageBrowserData.heading), pageControlForm);
        if(!pageBrowserData.currentPage) {
            // show page selector
            pageControlForm.append(hiddenProject(), hiddenMode(), pageChanger());
            if(mode !== "image") {
                // round selector not submitting if changed
                pageControlForm.append(" ", roundSelect(false));
            }
        } else {
            // if a page is given show it in scrollable area with controls
            // and a button to change project.
            pageControlForm.append(projectReset(), modeControl(), pageChanger());
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
                theTextControl.textArea.prop("readonly", true);
                stretchDiv.append(theTextControl.textArea);
                break;
            }
            case "imageText": {
                let theImageControl = imageControl();
                let theTextControl = textControl();
                theTextControl.textArea.prop("readonly", true);
                let imageDiv = $("<div>", {class: 'overflow-auto image-back'}).append(theImageControl.image);
                let textDiv = $("<div>").append(theTextControl.textArea);
                stretchDiv.append(imageDiv, textDiv);
                let theSplitter = viewSplitter(stretchDiv);
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

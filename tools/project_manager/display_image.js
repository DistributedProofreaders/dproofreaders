/*global $ mode pageBrowserData proofIntData imageControl pageChanger viewSplitter textControl
hiddenProject projectReset roundSelect projectSelector splitControl */

$(function () {
    // Construct the hidden modeInput to persist the mode
    // and appropriate buttons to switch to other modes
    var hiddenMode = function() {
        return $("<input>", {type: 'hidden', name: 'mode', value: mode});
    };

    var modeControl = function() {
        let modeButtons = {
            'image': [
                {type: 'text', caption: proofIntData.strings.hideImage},
                {type: 'imageText', caption: proofIntData.strings.showText}
            ],
            'text': [
                {type: 'imageText', caption: proofIntData.strings.showImage},
                {type: 'image', caption: proofIntData.strings.hideText}
            ],
            'imageText': [
                {type: 'text', caption: proofIntData.strings.hideImage},
                {type: 'image', caption: proofIntData.strings.hideText}
            ]
        };

        let modeInput = hiddenMode();
        let returnValue = [modeInput];

        modeButtons[mode].forEach(function (modeObj) {
            let modeButton = $("<input>", {type: 'submit', value: modeObj.caption});
            modeButton.click(function () {
                modeInput.val(modeObj.type);
            });
            returnValue.push(modeButton);
        });
        return returnValue;
    };

    let topDiv = $("#top-div");
    // the non-scrolling area which will contain any error messagfe and the form with controls
    let fixHead = $("<div>", {class: 'fixed-box control-form'});
    topDiv.append(fixHead);

    let pageControlForm = $("<form>", {method: "get"});

    if(pageBrowserData.errorMessage) {
        fixHead.append($("<div>", {class: 'error'}).append(pageBrowserData.errorMessage));
    }
    if(!pageBrowserData.projectid) {
        // just show the project input
        fixHead.append($("<div>").append(pageControlForm));
        pageControlForm.append(projectSelector(), hiddenMode());
    } else {
        // show project name
        fixHead.append($("<div>").append(pageBrowserData.heading)
            .append(pageControlForm));
        if(!pageBrowserData.currentPage) {
            // show page selector
            pageControlForm.append(hiddenProject(), hiddenMode(), pageChanger(pageControlForm));
            if(mode !== "image") {
                // round selector not submitting if changed
                pageControlForm.append(" ", roundSelect(false));
            }
        } else {
            // if a page is given show it in scrollable area with controls
            // and a button to change project.
            let displayRow = $("<div>").append(projectReset(), modeControl());
            pageControlForm.append(displayRow);
            let stretchDiv = $("<div>", {class: 'stretch-box'});
            topDiv.append(stretchDiv);

            let theImageControl = imageControl();
            let theTextControl = textControl();

            let textControlRow = $("<div>").append(roundSelect(true), theTextControl.controls);
            let imageControlRow = $("<div>").append(pageChanger(pageControlForm), theImageControl.controls);

            switch (mode) {
            // extra { } to use block-scoped variables here
            case "image": {
                pageControlForm.append(imageControlRow);
                stretchDiv.addClass("overflow-auto image-back").append(theImageControl.image);
                theImageControl.setZoom();
                break;
            }
            case "text": {
                // round selector submits if changed
                pageControlForm.append(textControlRow, pageChanger(pageControlForm));
                stretchDiv.append(theTextControl.textArea);
                break;
            }
            case "imageText": {
                let imageDiv = $("<div>", {class: 'overflow-auto image-back'}).append(theImageControl.image);
                let topTextDiv = $("<div>").append(theTextControl.textArea);
                let rulerDiv = $("<div>", {class: 'image-back'});
                let textDiv = $("<div>").append(topTextDiv, rulerDiv);
                stretchDiv.append(imageDiv, textDiv);
                let theSplitter = viewSplitter(stretchDiv);
                displayRow.append(theSplitter.buttons);
                pageControlForm.append(textControlRow, imageControlRow);
                theImageControl.setZoom();

                // set up ruler div split
                const rulerSplitterID = "ruler_split_percent";
                let rulerSplitPercent = localStorage.getItem(rulerSplitterID);
                if(!rulerSplitPercent) {
                    rulerSplitPercent = 100;
                }

                let rulerSplitter = splitControl();
                let rulerSplitterRef = rulerSplitter.setup(textDiv, {splitDirection: rulerSplitter.DIRECTION.HORIZONTAL, splitPercent: rulerSplitPercent, reDraw: theSplitter.mainSplit.reSize});
                rulerSplitterRef.dragEnd.add(function (percent) {
                    localStorage.setItem(rulerSplitterID, percent);
                });

                // re-layout after drawing fixed div
                theSplitter.mainSplit.reLayout();
                break;
            }
            }
        }
    }
});

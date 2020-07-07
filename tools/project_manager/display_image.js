/*global $ mode pageBrowserData proofIntData imageControl pageChanger viewSplitter textControl
topLine roundSelect hiddenMode projectInput pageInput projectSelectButton */

$(function () {
    // Construct the hidden modeInput to persist the mode
    // and appropriate buttons to switch to other modes
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
    let fixHead = $("<div>", {class: 'fix control-form'});
    let pageControlForm = $("<form>", {method: "get"});
    topDiv.append(fixHead);

    if(pageBrowserData.projectid) {
        let stretchDiv = $("<div>", {class: 'stretch'});
        topDiv.append(stretchDiv);
        fixHead.append(pageControlForm);
        pageControlForm.append(topLine(), modeControl());

        switch (mode) {
        // extra { } to use block-scoped variables here
        case "image": {
            let theImageControl = imageControl();
            // without the space, the two sets of controls seem to get
            // combined into a single nowrap span
            pageControlForm.append(theImageControl.controls, " ", pageChanger());

            stretchDiv.addClass("overflow-auto image-back").append(theImageControl.image);
            theImageControl.setZoom();
            break;
        }
        case "text": {
            let theTextControl = textControl();
            pageControlForm.append(pageChanger(), " ", roundSelect(true), theTextControl.controls);

            theTextControl.textArea.prop("readonly", true);
            stretchDiv.append(theTextControl.textArea);
            break;
        }
        case "imageText": {
            let theImageControl = imageControl();
            let theTextControl = textControl();
            let imageDiv = $("<div>").addClass('overflow-auto image-back')
                .append(theImageControl.image);
            let textDiv = $("<div>").append(theTextControl.textArea);
            stretchDiv.append(imageDiv, textDiv);
            let theSplitter = viewSplitter(stretchDiv);
            pageControlForm.append(theImageControl.controls, " ", pageChanger(), " ", roundSelect(true), theSplitter.buttons, theTextControl.controls);
            theImageControl.setZoom();
            theTextControl.textArea.prop("readonly", true);

            // re-layout after drawing fixed div
            theSplitter.mainSplit.reLayout();
            break;
        }
        }
    } else {
        if(pageBrowserData.errorMessage) {
            fixHead.append("<p class='error'>" + pageBrowserData.errorMessage + "</p>");
        }
        fixHead.append("<p>" + proofIntData.strings.selectAProject + "</p>", pageControlForm);
        pageControlForm.append(projectInput(), pageInput());
        if(mode !== "image") {
            pageControlForm.append(roundSelect(false));
        }
        pageControlForm.append(projectSelectButton(), hiddenMode());
    }
});

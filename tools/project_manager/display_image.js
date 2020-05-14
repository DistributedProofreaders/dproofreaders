/*global $ mode pageBrowserData attrSafe dpData hiddenProject imageControl pageChanger splitter textControl roundSelect*/

$(function () {
    var modeControl = function() {
        let modeObjs = [
            {type: 'image', caption: dpData.strings.showImage},
            {type: 'text', caption: dpData.strings.showText},
            {type: 'imageText', caption: dpData.strings.showImageText}
        ];

        let modeInput = $("<input>", {type: 'hidden', name: 'mode', value: mode});
        let returnValue = [modeInput];

        modeObjs.forEach(function (modeObj) {
            if(modeObj.type !== mode) {
                let modeButton = $("<input>", {type: 'submit', value: attrSafe(modeObj.caption)});
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
    let stretchDiv = $("<div>", {class: 'stretch'});

    topDiv.append(fixHead, stretchDiv);

    let pageControlForm = $("<form>", {method: "get"});

    fixHead.append(pageBrowserData.heading, pageControlForm);

    pageControlForm.append(hiddenProject(), modeControl());

    switch (mode) {
    // use block-scoped variables here
    case "image": {
        let theImageControl = imageControl();
        pageControlForm.append(theImageControl.controls, pageChanger());

        stretchDiv.addClass("overflow-auto image-back").append(theImageControl.image);
        theImageControl.setZoom();
        break;
    }
    case "text": {
        let theTextControl = textControl();
        pageControlForm.append(pageChanger(), roundSelect(), theTextControl.controls);

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
        let theSplitter = splitter(stretchDiv);
        pageControlForm.append(theImageControl.controls, pageChanger(), roundSelect(), theSplitter.buttons, theTextControl.controls);

        theImageControl.setZoom();

        theTextControl.textArea.prop("readonly", true);

        // re-layout after drawing fixed div
        theSplitter.mainSplit.reLayout();
        break;
    }
    }
});

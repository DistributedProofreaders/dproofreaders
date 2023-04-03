/*global $ splitControl proofIntData makeControlDiv */
/*exported viewSplitter makeTextWidget */

// Construct the font-face, font-size and wrap controls
var maketextControl = function(textArea) {
    let textKey;
    let textData;

    function saveData() {
        localStorage.setItem(textKey, JSON.stringify(textData));
    }

    const fontFaceSelector = document.createElement("select");
    fontFaceSelector.title = proofIntData.strings.changeFontFace;
    const fontSizeSelector = document.createElement("select");
    fontSizeSelector.title = proofIntData.strings.changeFontSize;

    function setFontFace(fontFaceIndex) {
        textArea.css("font-family", proofIntData.font.faceFamilies[fontFaceIndex]);
    }

    // set up the font selector
    const fontFaces = proofIntData.font.faces;
    Object.keys(fontFaces).forEach(function(index) {
        fontFaceSelector.add(new Option(fontFaces[index], index));
    });

    $(fontFaceSelector).change(function () {
        const fontFaceIndex = this.value;
        setFontFace(fontFaceIndex);
        textData.fontFaceIndex = fontFaceIndex;
        saveData();
    });

    function setFontSize(fontSize) {
        const fontSizeCss = (fontSize === '') ? 'unset' : fontSize;
        textArea.css("font-size", fontSizeCss);
    }

    proofIntData.font.sizes.forEach(function(fontSize) {
        const displayFontSize = (fontSize === '') ? proofIntData.strings.browserDefault : fontSize;
        fontSizeSelector.add(new Option(displayFontSize, fontSize));
    });

    $(fontSizeSelector).change(function () {
        const fontSize = fontSizeSelector.value;
        setFontSize(fontSize);
        textData.fontSize = fontSize;
        saveData();
    });

    function setWrap(textWrap) {
        textArea.attr('wrap', textWrap ? 'soft' : 'off');
    }

    const wrapCheck = $("<input>", {type: 'checkbox'});

    wrapCheck.change(function () {
        const textWrap = wrapCheck.prop("checked");
        textData.textWrap = textWrap ? "W" : "N";
        saveData();
        setWrap(textWrap);
    });

    const wrapControl = $("<label>", {class: "nowrap", text: proofIntData.strings.wrap}).append(wrapCheck);

    return {
        controls: [fontFaceSelector, fontSizeSelector, wrapControl],
        setup: function(storageKey) {
            textKey = storageKey + "-text";
            textData = JSON.parse(localStorage.getItem(textKey));
            if(!$.isPlainObject(textData)) {
                textData = {
                    textWrap: "N",
                    fontFaceIndex: 0,
                    fontSize: ""
                };
            }
            // find the corresponding selector option and select it
            fontFaceSelector.querySelector(`[value="${textData.fontFaceIndex}"]`).selected = true;
            // use value from selector incase the user defined option has been
            // removed and value has changed from 1 to 0
            setFontFace(fontFaceSelector.value);

            const currentFontSize = textData.fontSize;
            fontSizeSelector.querySelector(`[value="${currentFontSize}"]`).selected = true;
            setFontSize(currentFontSize);

            // stored value is "W" or "N", if not set textWrap will be false
            const textWrap = ("W" === textData.textWrap);
            wrapCheck.prop("checked", textWrap);
            setWrap(textWrap);
        },
    };
};

function makeTextWidget(container, splitter = false, reLayout = null, leftSplit = true) {
    const textArea = $("<textarea>", {class: "text-pane"});
    textArea.prop("readonly", !splitter);
    const textControl = maketextControl(textArea);
    const content = $("<div>");
    const controlDiv = makeControlDiv(container, content, textControl.controls, reLayout);
    let subSplitter;
    let leftSplitter;
    let splitterKey;
    let leftSplitKey;
    let textSplitData;
    let leftSplitData;
    if(splitter) {
        const topTextDiv = $("<div>", {class: "display-flex"});
        const bottomTextDiv = $("<div>");
        content.append(topTextDiv, bottomTextDiv);
        subSplitter = splitControl(content, {splitVertical: false, reDraw: reLayout});
        subSplitter.dragEnd.add(function (percent) {
            textSplitData.splitPercent = percent;
            localStorage.setItem(splitterKey, JSON.stringify(textSplitData));
        });
        if(leftSplit) {
            const leftDiv = $("<div>");
            const rightDiv = $("<div>", {class: "display-flex"}).append(textArea);
            topTextDiv.append(leftDiv, rightDiv);
            leftSplitter = splitControl(topTextDiv, {splitVertical: true, reDraw: subSplitter.reSize});
            leftSplitter.dragEnd.add(function (percent) {
                leftSplitData.splitPercent = percent;
                localStorage.setItem(leftSplitKey, JSON.stringify(leftSplitData));
            });
        } else {
            topTextDiv.append(textArea);
        }

    } else {
        content.addClass("display-flex").append(textArea);
    }
    return {
        setup: function(storageKey) {
            const textWidgetKey = storageKey + "-textwidget";
            if(splitter) {
                splitterKey = textWidgetKey + "-split";
                textSplitData = JSON.parse(localStorage.getItem(splitterKey));
                if(!$.isPlainObject(textSplitData)) {
                    textSplitData = {splitPercent: 100};
                }
                subSplitter.setSplitPercent(textSplitData.splitPercent);
                if(leftSplit) {
                    leftSplitKey = textWidgetKey + "-leftsplit";
                    leftSplitData = JSON.parse(localStorage.getItem(leftSplitKey));
                    if(!leftSplitData) {
                        leftSplitData = {splitPercent: 10};
                    }
                    leftSplitter.setSplitPercent(leftSplitData.splitPercent);
                }
            }
            textControl.setup(textWidgetKey);
            controlDiv.setupControls(textWidgetKey);
        },
        setText: function (text) {
            textArea.val(text)
                .scrollTop(0)
                .scrollLeft(0);
        },
        getText: function () {
            return textArea.val();
        },
        textArea: textArea[0],
    };
}

// Construct the buttons for horizontal/vertical split
// and return a splitter variable.
var viewSplitter = function(container, storageKey) {
    const storageKeyLayout = storageKey + "-layout";
    let layout = JSON.parse(localStorage.getItem(storageKeyLayout));
    if(!$.isPlainObject(layout)) {
        layout = {splitDirection: "horizontal"};
    }
    const splitVertical = (layout.splitDirection === "vertical");

    const mainSplit = splitControl(container, {splitVertical: splitVertical});

    const imageButtonSize = 26;
    const vSplitImage = $("<img>", {src: proofIntData.buttonImages.imgVSplit, height: imageButtonSize, width: imageButtonSize});
    const vSwitchButton = $("<button>", {type: 'button', class: 'img-button control', title: proofIntData.strings.switchVert}).append(vSplitImage);
    const hSplitImage = $("<img>", {src: proofIntData.buttonImages.imgHSplit, height: imageButtonSize, width: imageButtonSize});
    const hSwitchButton = $("<button>", {type: 'button', class: 'img-button control', title: proofIntData.strings.switchHoriz}).append(hSplitImage);

    let splitKey;
    const setSplitDirCallback = $.Callbacks();
    setSplitDirCallback.add(function(storageKey) {
        // get the split percent for vertical or horizontal
        splitKey = storageKey + "-split";
        let directionData = JSON.parse(localStorage.getItem(splitKey));
        if(!$.isPlainObject(directionData)) {
            directionData = {splitPercent: 50};
        }
        mainSplit.setSplitPercent(directionData.splitPercent);
    });

    function setSplitControls(splitVert) {
        if (splitVert) {
            hSwitchButton.show();
            vSwitchButton.hide();
        } else {
            hSwitchButton.hide();
            vSwitchButton.show();
        }
    }

    function fireSetSplitDir() {
        setSplitDirCallback.fire(storageKey + "-" + layout.splitDirection);
    }

    function changeSplit(splitVert) {
        mainSplit.setSplit(splitVert);
        setSplitControls(splitVert);
        layout.splitDirection = splitVert ? "vertical" : "horizontal";
        localStorage.setItem(storageKeyLayout, JSON.stringify(layout));
        fireSetSplitDir();
    }

    vSwitchButton.click(function () {
        changeSplit(true);
    });

    hSwitchButton.click(function () {
        changeSplit(false);
    });

    setSplitControls(splitVertical);

    mainSplit.dragEnd.add(function (percent) {
        localStorage.setItem(splitKey, JSON.stringify({splitPercent: percent}));
    });

    return {
        mainSplit,
        buttons: [vSwitchButton, hSwitchButton],
        setSplitDirCallback,
        fireSetSplitDir,
    };
};

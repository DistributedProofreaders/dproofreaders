/*global $ proofIntData splitControl makeControlDiv */
/* exported makeTextWidget viewSplitter */

function makeTextWidget(container, splitter = false) {
    const textArea = document.createElement("textarea");
    textArea.classList.add("text-pane");
    textArea.readOnly = !splitter;

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
        textArea.style.fontFamily = proofIntData.font.faceFamilies[fontFaceIndex];
    }

    // set up the font selector
    const fontFaces = proofIntData.font.faces;
    Object.keys(fontFaces).forEach(function(index) {
        fontFaceSelector.add(new Option(fontFaces[index], index));
    });

    fontFaceSelector.addEventListener("change", function () {
        const fontFaceIndex = this.value;
        setFontFace(fontFaceIndex);
        textData.fontFaceIndex = fontFaceIndex;
        saveData();
    });

    function setFontSize(fontSize) {
        const fontSizeCss = (fontSize === '') ? 'unset' : fontSize;
        textArea.style.fontSize = fontSizeCss;
    }

    proofIntData.font.sizes.forEach(function(fontSize) {
        const displayFontSize = (fontSize === '') ? proofIntData.strings.browserDefault : fontSize;
        fontSizeSelector.add(new Option(displayFontSize, fontSize));
    });

    fontSizeSelector.addEventListener("change", function () {
        const fontSize = fontSizeSelector.value;
        setFontSize(fontSize);
        textData.fontSize = fontSize;
        saveData();
    });

    function setWrap(textWrap) {
        textArea.wrap = textWrap ? 'soft' : 'off';
    }

    const wrapCheck = $("<input>", {type: 'checkbox'});

    wrapCheck.change(function () {
        const textWrap = wrapCheck.prop("checked");
        textData.textWrap = textWrap ? "W" : "N";
        saveData();
        setWrap(textWrap);
    });

    const wrapControl = $("<label>", {class: "nowrap", text: proofIntData.strings.wrap}).append(wrapCheck);

    const content = $("<div>");
    const controls = [fontFaceSelector, fontSizeSelector, wrapControl];
    const controlDiv = makeControlDiv(container, content, controls);
    let subSplitter;
    let splitterKey;
    let textSplitData;
    if(splitter) {
        const topTextDiv = $("<div>", {class: "display-flex"}).append(textArea);
        const bottomTextDiv = $("<div>");
        content.append(topTextDiv, bottomTextDiv);

        subSplitter = splitControl(content, {splitVertical: false});
        controlDiv.onChange.add(subSplitter.reLayout);

        subSplitter.onDragEnd.add(function (percent) {
            textSplitData.splitPercent = percent;
            localStorage.setItem(splitterKey, JSON.stringify(textSplitData));
        });
    } else {
        content.addClass("display-flex").append(textArea);
    }
    return {
        setup: function(storageKey) {
            const textWidgetKey = storageKey + "-textwidget";
            if(splitter) {
                splitterKey = textWidgetKey + "-split";
                textSplitData = JSON.parse(localStorage.getItem(splitterKey));
                if(!textSplitData ||
                   (typeof textSplitData.splitPercent !== 'number' &&
                    typeof textSplitData.splitPercent !== 'string')) {
                    textSplitData = {splitPercent: 100};
                }
                subSplitter.setSplitPercent(textSplitData.splitPercent);
            }
            textKey = textWidgetKey + "-text";
            textData = JSON.parse(localStorage.getItem(textKey));
            if(!textData ||
                typeof textData.textWrap !== "string" ||
                (typeof textData.fontFaceIndex !== "number" &&
                typeof textData.fontFaceIndex !== "string") ||
                typeof textData.fontSize !== "string") {
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

            controlDiv.setupControls(textWidgetKey);
        },

        setText: function (text) {
            textArea.value = text;
            textArea.scrollTop = 0;
            textArea.scrollLeft = 0;
        },

        reLayout: function () {
            if(splitter) {
                subSplitter.reLayout();
            }
        },
    };
}

// Construct the button for horizontal/vertical split
// and return a splitter variable.
var viewSplitter = function(container, storageKey) {
    const storageKeyLayout = storageKey + "-layout";
    let layout = JSON.parse(localStorage.getItem(storageKeyLayout));
    if(!layout || (layout.splitDirection !== "horizontal" && layout.splitDirection !== "vertical")) {
        layout = {splitDirection: "horizontal"};
    }
    let splitVertical = (layout.splitDirection === "vertical");

    const mainSplit = splitControl(container, {splitVertical: splitVertical});
    window.addEventListener("resize", mainSplit.reLayout);

    const imageButtonSize = 26;
    const splitImage = document.createElement("img");
    splitImage.height = imageButtonSize;
    splitImage.width = imageButtonSize;
    splitImage.classList.add('img-button', 'control');

    let splitKey;
    const setSplitDirCallback = [];
    setSplitDirCallback.push(function(storageKey) {
        // get the split percent for vertical or horizontal
        splitKey = storageKey + "-split";
        let directionData = JSON.parse(localStorage.getItem(splitKey));
        if(!directionData ||
           (typeof directionData.splitPercent !== 'number' &&
            typeof directionData.splitPercent !== 'string')) {
            directionData = {splitPercent: 50};
        }
        mainSplit.setSplitPercent(directionData.splitPercent);
    });

    function setSplitControls() {
        splitImage.src = splitVertical ? proofIntData.buttonImages.imgHSplit : proofIntData.buttonImages.imgVSplit;
    }

    function fireSetSplitDir() {
        setSplitDirCallback.forEach(function (setSplitDirCallbackFunction) {
            setSplitDirCallbackFunction(storageKey + "-" + layout.splitDirection);
        });
        mainSplit.reLayout();
    }

    splitImage.addEventListener("click", function () {
        splitVertical = !splitVertical;
        layout.splitDirection = splitVertical ? "vertical" : "horizontal";
        localStorage.setItem(storageKeyLayout, JSON.stringify(layout));
        mainSplit.setSplit(splitVertical);
        setSplitControls();
        fireSetSplitDir();
    });

    setSplitControls();

    mainSplit.onDragEnd.add(function (percent) {
        localStorage.setItem(splitKey, JSON.stringify({splitPercent: percent}));
    });

    return {
        mainSplit,
        button: splitImage,
        setSplitDirCallback,
        fireSetSplitDir,
    };
};

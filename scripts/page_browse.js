/* global $ pageBrowserData proofIntData splitControl */
/* exported viewSplitter hiddenProject projectSelector projectReset
pageChanger imageControl textControl roundSelect */

var projectSelector = function() {
    return [
        $("<span>", {class: "nowrap"}).append(proofIntData.strings.projectid, " ", $("<input>", {type: 'text', name: 'project', required: true})),
        $("<input>", {type: 'submit', value: proofIntData.strings.selectProject})
    ];
};

var hiddenProject = function() {
    return $("<input>", {type: 'hidden', name: 'project', value: pageBrowserData.projectid});
};

var projectReset = function() {
    let resetButton = $("<input>", {type: 'submit', value: proofIntData.strings.reset});
    let hiddenProjectInput = hiddenProject();
    resetButton.click(function () {
        hiddenProjectInput.val("");
    });

    return [resetButton, hiddenProjectInput];
};

// Construct the controls to change pages
var pageChanger = function (theForm) {
    let pageSelector = document.createElement("select");
    pageSelector.name = 'imagefile';

    $(pageSelector).change(function() {
        this.form.submit();
    });

    function prevEnabled() {
        return pageSelector.selectedIndex > 0;
    }

    function nextEnabled() {
        return pageSelector.selectedIndex < (pageSelector.length - 1);
    }

    let retVal = $("<span>", {class: "nowrap"}).append(proofIntData.strings.page + " ", pageSelector);

    // if no page is given add a "select a page" line
    if(!pageBrowserData.currentPage) {
        pageSelector.required = true;
        let firstOption = new Option(proofIntData.strings.selectAPage, 0, true, true);
        firstOption.disabled = true;
        pageSelector.add(firstOption);
        pageBrowserData.pages.forEach(function(page) {
            pageSelector.add(new Option(page, page, false, false));
        });

    } else {
        pageBrowserData.pages.forEach(function(page) {
            let selected = pageBrowserData.currentPage === page;
            pageSelector.add(new Option(page, page, selected, selected));
        });
        let prevButton = $("<input>", {type: 'submit', value: proofIntData.strings.previous});
        let nextButton = $("<input>", {type: 'submit', value: proofIntData.strings.next});

        prevButton.prop("disabled", !prevEnabled());
        nextButton.prop("disabled", !nextEnabled());

        prevButton.click(function () {
            pageSelector.selectedIndex -= 1;
        });

        nextButton.click(function () {
            pageSelector.selectedIndex += 1;
        });

        $(document).keydown(function(event) {
            if(event.altKey === true) {
                if((event.which === 38) && prevEnabled()) {
                    // up arrow
                    pageSelector.selectedIndex -= 1;
                    theForm.submit();
                } else if((event.which === 40) && nextEnabled()) {
                    // down arrow
                    pageSelector.selectedIndex += 1;
                    theForm.submit();
                }
            }
        });

        retVal.append(prevButton, nextButton);
    }
    return retVal;
};

// Construct the buttons for horizontal/vertical split
// and return a splitter variable.
var viewSplitter = function(container) {
    const textImageSplitID = "text_image_split";
    const splitPercentID = "split_percent";

    // stored value is "horizontal" or "vertical"
    let splitDirString = localStorage.getItem(textImageSplitID);
    if(!splitDirString) {
        splitDirString = "horizontal";
    }
    // splitVertical is true or false
    let splitVertical = (splitDirString === "vertical");

    let splitPercent = localStorage.getItem(splitPercentID);
    if(!splitPercent) {
        splitPercent = 50;
    }

    let mainSplit = splitControl(container, {splitVertical: splitVertical, splitPercent: splitPercent});

    let vSplitImage = $("<img>", {src: proofIntData.buttonImages.imgVSplit});
    let vSwitchButton = $("<button>", {type: 'button', class: 'img-button', title: proofIntData.strings.switchVert}).append(vSplitImage);
    let hSplitImage = $("<img>", {src: proofIntData.buttonImages.imgHSplit});
    let hSwitchButton = $("<button>", {type: 'button', class: 'img-button', title: proofIntData.strings.switchHoriz}).append(hSplitImage);
    // The splitter could be laid out before images are loaded so that when
    // images appear some controls could be pushed out of view
    vSplitImage.on("load", mainSplit.reLayout);
    hSplitImage.on("load", mainSplit.reLayout);

    function setSplitControls(splitVertical) {
        if (splitVertical) {
            hSwitchButton.show();
            vSwitchButton.hide();
        } else {
            hSwitchButton.hide();
            vSwitchButton.show();
        }
    }

    function changeSplit(splitVertical) {
        mainSplit.setSplit(splitVertical);
        setSplitControls(splitVertical);
        splitDirString = splitVertical ? "vertical" : "horizontal";
        localStorage.setItem(textImageSplitID, splitDirString);
    }

    vSwitchButton.click(function () {
        changeSplit(true);
    });

    hSwitchButton.click(function () {
        changeSplit(false);
    });

    setSplitControls(splitVertical);

    mainSplit.dragEnd.add(function (percent) {
        localStorage.setItem(splitPercentID, percent);
    });

    return {
        mainSplit: mainSplit,
        buttons: [vSwitchButton, hSwitchButton],
    };
};

// Construct the image sizing controls.
var imageControl = function() {
    const imagePercentID = "image_percent";
    let percent = localStorage.getItem(imagePercentID);
    if(!percent) {
        percent = 100;
    }

    let percentInput = $("<input>", {type: 'number', min: '1', max: '999', value: percent});

    let image = $("<img>", {src: pageBrowserData.imageUrl});

    let setZoom = function () {
        image.width(10 * percent);
        image.height("auto");
    };

    percentInput.change(function() {
        percent = this.value;
        localStorage.setItem(imagePercentID, percent);
        setZoom();
    });

    return {
        image: image,
        controls: $("<span>", {class: "nowrap"}).append(proofIntData.strings.zoom + " ", percentInput, "% "),
        setZoom: setZoom,
    };
};

// Construct the font-face, font-size and wrap controls
var textControl = function() {
    let textArea = $("<textarea>", {class: "full-text"});
    textArea.val(pageBrowserData.text);
    let fontFaceSelector = document.createElement("select");
    fontFaceSelector.title = proofIntData.strings.changeFontFace;
    let fontSizeSelector = document.createElement("select");
    fontSizeSelector.title = proofIntData.strings.changeFontSize;

    const fontFaceID = "font_face";
    const fontSizeID = "font_size";
    const wrapID = "text_wrap";

    function setFontFace(fontFaceIndex) {
        textArea.css("font-family", proofIntData.font.faceFamilies[fontFaceIndex]);
    }

    let currentFontFaceIndex = localStorage.getItem(fontFaceID);

    // set up the font selector
    let fontFaces = proofIntData.font.faces;
    Object.keys(fontFaces).forEach(function(index) {
        let selected = (index === currentFontFaceIndex);
        fontFaceSelector.add(new Option(fontFaces[index], index, selected, selected));
    });
    // use value from selector incase the user defined option has been
    // removed and value has changed from 1 to 0
    setFontFace(fontFaceSelector.value);

    $(fontFaceSelector).change(function () {
        let fontFaceIndex = this.value;
        setFontFace(fontFaceIndex);
        localStorage.setItem(fontFaceID, fontFaceIndex);
    });

    function setFontSize(fontSizeIndex) {
        textArea.css("font-size", proofIntData.font.sizeFamilies[fontSizeIndex]);
    }

    let currentFontSizeIndex = localStorage.getItem(fontSizeID);

    let fontSizes = proofIntData.font.sizes;
    Object.keys(fontSizes).forEach(function(fontSizeIndex) {
        let selected = (currentFontSizeIndex === fontSizeIndex);
        fontSizeSelector.add(new Option(fontSizes[fontSizeIndex], fontSizeIndex, selected, selected));
    });
    setFontSize(currentFontSizeIndex);

    $(fontSizeSelector).change(function () {
        let fontSizeIndex = fontSizeSelector.value;
        setFontSize(fontSizeIndex);
        localStorage.setItem(fontSizeID, fontSizeIndex);
    });

    function setWrap(textWrap) {
        textArea.attr('wrap', textWrap ? 'soft' : 'off');
    }

    let wrapCheck = $("<input>", {type: 'checkbox'});

    // stored value is "W" or "N", if not set textWrap will be false
    let textWrap = ("W" === localStorage.getItem(wrapID));
    wrapCheck.prop("checked", textWrap);
    setWrap(textWrap);

    wrapCheck.change(function () {
        textWrap = wrapCheck.prop("checked");
        localStorage.setItem(wrapID, textWrap ? "W" : "N");
        setWrap(textWrap);
    });

    return {
        textArea: textArea,
        controls: [fontFaceSelector, fontSizeSelector, $("<label>", {text: proofIntData.strings.wrap, class: "nowrap"}).append(wrapCheck)],
    };
};

var roundSelect = function(autochange) {
    let roundSelector = document.createElement("select");
    roundSelector.name = 'round_id';

    proofIntData.expandedRounds.forEach(function(round) {
        let selected = pageBrowserData.currentRound === round;
        roundSelector.add(new Option(round, round, selected, selected));
    });

    if(autochange) {
        $(roundSelector).change(function() {
            this.form.submit();
        });
    }

    return $("<span>", {class: "nowrap"}).append(proofIntData.strings.round + " ", roundSelector);
};

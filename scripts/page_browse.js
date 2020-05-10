/* global $ pageBrowserData dpData initSplit */
/* exported hiddenProject pageChanger splitter imageControl textControl roundSelect */

function attrSafe(str) {
    return str.replace("'", "&apos;");
}

var hiddenProject = function() {
    return $("<input>", {type: 'hidden', name: 'project', value: pageBrowserData.projectid});
};

var pageChanger = function () {
    let pageSelector = document.createElement("select");
    pageSelector.name = 'imagefile';
    let prevButton = $("<input>", {type: 'submit', value: attrSafe(dpData.strings.previous)});
    let nextButton = $("<input>", {type: 'submit', value: attrSafe(dpData.strings.next)});

    pageBrowserData.pages.forEach(function(page) {
        let selected = pageBrowserData.currentPage === page;
        pageSelector.add(new Option(page, page, selected, selected));
    });

    $(pageSelector).change(function() {
        this.form.submit();
    });

    prevButton.prop("disabled", pageSelector.selectedIndex === 0);
    nextButton.prop("disabled", pageSelector.selectedIndex === (pageSelector.length - 1));

    prevButton.click(function () {
        pageSelector.selectedIndex -= 1;
    });

    nextButton.click(function () {
        pageSelector.selectedIndex += 1;
    });
    return [dpData.strings.page, ": ", pageSelector, prevButton, nextButton];
};


var splitter = function(container) {
    const textImageSplitID = "text_image_split";
    const splitPercentID = "split_percent";
    // stored value is "H" or "V", vSplit is true or false
    // if not set vSplit will be false
    let vSplit = ("V" === localStorage.getItem(textImageSplitID));
    var splitPercent = localStorage.getItem(splitPercentID);
    if(!splitPercent) {
        splitPercent = 50;
    }
    var mainSplit;

    let vSwitchButton = $("<button>", {type: 'button', class: 'img-button', title: attrSafe(dpData.strings.switchVert)}).append($("<img>", {src: dpData.buttonImages.imgVSplit}));
    let hSwitchButton = $("<button>", {type: 'button', class: 'img-button', title: attrSafe(dpData.strings.switchHoriz)}).append($("<img>", {src: dpData.buttonImages.imgHSplit}));

    function setSplitControls(vSplit) {
        if (vSplit) {
            hSwitchButton.show();
            vSwitchButton.hide();
        } else {
            hSwitchButton.hide();
            vSwitchButton.show();
        }
    }

    function changeSplit(vSplit) {
        mainSplit.setSplit(vSplit);
        setSplitControls(vSplit);
        localStorage.setItem(textImageSplitID, vSplit ? "V" : "H");
    }

    vSwitchButton.click(function () {
        changeSplit(true);
    });

    hSwitchButton.click(function () {
        changeSplit(false);
    });

    mainSplit = initSplit({paneContainer: container, verticalSplit: vSplit, splitPercent: splitPercent});
    setSplitControls(vSplit);

    $(window).resize(mainSplit.reLayout);

    mainSplit.dragEnd.add(function (percent) {
        localStorage.setItem(splitPercentID, percent);
    });

    return {
        mainSplit: mainSplit,
        buttons: [vSwitchButton, hSwitchButton],
    };
};

var imageControl = function() {
    const imagePercentID = "image_percent";
    var percent = localStorage.getItem(imagePercentID);
    if(!percent) {
        percent = 100;
    }

    let percentInput = $("<input>", {type: 'number', min: '1', max: '999', value: percent});
    let resizeButton = $("<input>", {type: 'button', value: dpData.strings.resize});

    let image = $("<img>", {src: dpData.projectsUrl + "/" + pageBrowserData.projectid + "/" + pageBrowserData.currentPage});

    var setZoom = function () {
        image.width(10 * percent);
        image.height("auto");
    };

    resizeButton.click(function () {
        percent = percentInput.val();
        localStorage.setItem(imagePercentID, percent);
        setZoom();
    });

    return {
        image: image,
        controls: [dpData.strings.image, ": ", percentInput, "% ", resizeButton],
        setZoom: setZoom,
    };
};

var textControl = function() {
    // this operates the font face, font size and wrap controls
    let textArea = $("<textarea>", {class: "full-text"});
    // use val here, append will remove apparent html
    textArea.val(pageBrowserData.text);
    let fontFaceSelector = document.createElement("select");
    fontFaceSelector.title = attrSafe(dpData.strings.changeFontFace);
    let fontSizeSelector = document.createElement("select");
    fontSizeSelector.title = attrSafe(dpData.strings.changeFontSize);
    let wrapCheck = $("<input>", {type: 'checkbox', id: 'wrap'});

    const fontFaceID = "font_face";
    const fontSizeID = "font_size";
    const wrapID = "text_wrap";

    function setFontFace(fontFace) {
        textArea.css("font-family", fontFace);
    }

    var currentFontFace = localStorage.getItem(fontFaceID);

    dpData.font.faces.forEach(function(fontFace) {
        let selected = currentFontFace === fontFace;
        fontFaceSelector.add(new Option(fontFace, fontFace, selected, selected));
    });
    setFontFace(currentFontFace);

    $(fontFaceSelector).change(function () {
        let fontFace = fontFaceSelector.value;
        setFontFace(fontFace);
        localStorage.setItem(fontFaceID, fontFace);
    });

    function setFontSize(fontSize) {
        textArea.css("font-size", fontSize);
    }

    var currentFontSize = localStorage.getItem(fontSizeID);

    let fontSizes = dpData.font.sizes;
    Object.keys(fontSizes).forEach(function(fontSize) {
        let selected = currentFontSize === fontSize;
        fontSizeSelector.add(new Option(fontSizes[fontSize], fontSize, selected, selected));
    });
    setFontSize(currentFontSize);

    $(fontSizeSelector).change(function () {
        let fontSize = fontSizeSelector.value;
        setFontSize(fontSize);
        localStorage.setItem(fontSizeID, fontSize);
    });

    function setWrap(textWrap) {
        textArea.attr('wrap', textWrap ? 'soft' : 'off');
    }

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
        controls: [fontFaceSelector, fontSizeSelector, $("<label>", {for: 'wrap'}).text(dpData.strings.wrap), wrapCheck],
    };
};

var roundSelect = function() {
    let roundSelector = document.createElement("select");
    roundSelector.name = 'round_id';

    dpData.expandedRounds.forEach(function(round) {
        let selected = pageBrowserData.currentRound === round;
        roundSelector.add(new Option(round, round, selected, selected));
    });

    $(roundSelector).change(function() {
        this.form.submit();
    });

    return [dpData.strings.round, ": ", roundSelector];
};

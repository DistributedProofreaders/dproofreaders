/*global $ proofIntData makeApiAjaxSettings splitControl makeImageWidget makeControlDiv */
/* exported pageBrowse */

// Construct the font-face, font-size and wrap controls
var maketextControl = function(textArea, storageKey) {

    let textKey = storageKey + "-text";
    let textData = JSON.parse(localStorage.getItem(textKey));
    if(!$.isPlainObject(textData)) {
        textData = {
            textWrap: "N",
            fontFaceIndex: 0,
            fontSize: ""
        };
    }

    function saveData() {
        localStorage.setItem(textKey, JSON.stringify(textData));
    }

    let fontFaceSelector = document.createElement("select");
    fontFaceSelector.title = proofIntData.strings.selectFontFace;
    let fontSizeSelector = document.createElement("select");
    fontSizeSelector.title = proofIntData.strings.selectFontSize;

    function setFontFace(fontFaceIndex) {
        textArea.css("font-family", proofIntData.font.faceFamilies[fontFaceIndex]);
    }

    let currentFontFaceIndex = textData.fontFaceIndex;

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
        textData.fontFaceIndex = fontFaceIndex;
        saveData();
    });

    function setFontSize(fontSize) {
        let fontSizeCss = (fontSize === '') ? 'unset' : fontSize;
        textArea.css("font-size", fontSizeCss);
    }

    let currentFontSize = textData.fontSize;

    proofIntData.font.sizes.forEach(function(fontSize) {
        let selected = (currentFontSize === fontSize);
        let displayFontSize = (fontSize === '') ? proofIntData.strings.browserDefault : fontSize;
        fontSizeSelector.add(new Option(displayFontSize, fontSize, selected, selected));
    });
    setFontSize(currentFontSize);

    $(fontSizeSelector).change(function () {
        let fontSize = fontSizeSelector.value;
        setFontSize(fontSize);
        textData.fontSize = fontSize;
        saveData();
    });

    function setWrap(textWrap) {
        textArea.attr('wrap', textWrap ? 'soft' : 'off');
    }

    let wrapCheck = $("<input>", {type: 'checkbox'});

    // stored value is "W" or "N", if not set textWrap will be false
    let textWrap = ("W" === textData.textWrap);
    wrapCheck.prop("checked", textWrap);
    setWrap(textWrap);

    wrapCheck.change(function () {
        textWrap = wrapCheck.prop("checked");
        textData.textWrap = textWrap ? "W" : "N";
        saveData();
        setWrap(textWrap);
    });

    let wrapControl = $("<label>", {class: "nowrap", text: proofIntData.strings.wrap, title: proofIntData.strings.lineWrap}).append(wrapCheck);

    return [fontFaceSelector, fontSizeSelector, wrapControl];
};

function makeTextWidget(container, storageKey, readOnly = true) {
    let textWidgetKey = storageKey + "-textwidget";
    let textArea = $("<textarea>", {class: "text-pane"});
    let content = $("<div>");
    textArea.prop("readonly", readOnly);
    let controls = maketextControl(textArea, textWidgetKey);
    makeControlDiv(container, content, controls, textWidgetKey);
    content.append(textArea);
    return {
        setText: function (text) {
            textArea.val(text)
                .scrollTop(0)
                .scrollLeft(0);
        }
    };
}

// Construct the buttons for horizontal/vertical split
// and return a splitter variable.
var viewSplitter = function(container, storageKey) {
    let storageKeyLayout = storageKey + "-layout";
    let layout = JSON.parse(localStorage.getItem(storageKeyLayout));
    if(!$.isPlainObject(layout)) {
        layout = {splitPercent: 50, splitDirection: "horizontal"};
    }
    let splitVertical = (layout.splitDirection === "vertical");

    function saveLayout() {
        localStorage.setItem(storageKeyLayout, JSON.stringify(layout));
    }

    let mainSplit = splitControl(container, {splitVertical: splitVertical, splitPercent: layout.splitPercent});

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
        layout.splitDirection = splitVertical ? "vertical" : "horizontal";
        saveLayout();
    }

    vSwitchButton.click(function () {
        changeSplit(true);
    });

    hSwitchButton.click(function () {
        changeSplit(false);
    });

    setSplitControls(splitVertical);

    mainSplit.dragEnd.add(function (percent) {
        layout.splitPercent = percent;
        saveLayout();
    });

    return {
        mainSplit: mainSplit,
        buttons: [vSwitchButton, hSwitchButton],
    };
};

function makePageControl(pages, selectedImageFileName, changePage) {
    // changePage is a callback to act when page changes
    let pageSelector = document.createElement("select");
    pageSelector.title = proofIntData.strings.page;

    if(!selectedImageFileName) {
        // when no page is defined, "Select a page" option is added
        pageSelector.required = true;
        let firstOption = new Option(proofIntData.strings.selectAPage, 0, true, true);
        firstOption.disabled = true;
        pageSelector.add(firstOption);
        pages.forEach(function(page, index) {
            pageSelector.add(new Option(page.image, index, false, false));
        });

        $(pageSelector).change( function() {
            changePage(pages[pageSelector.value]);
        });

        return [pageSelector];
    }

    pages.forEach(function(page, index) {
        let imageFilename = page.image;
        let selected = (selectedImageFileName === imageFilename);
        pageSelector.add(new Option(imageFilename, index, selected, selected));
    });

    let prevButton = $("<input>", {type: 'button', value: proofIntData.strings.previous, title: proofIntData.strings.previousPage});
    let nextButton = $("<input>", {type: 'button', value: proofIntData.strings.next, title: proofIntData.strings.nextPage});

    function prevEnabled() {
        return pageSelector.selectedIndex > 0;
    }

    function nextEnabled() {
        return pageSelector.selectedIndex < (pageSelector.length - 1);
    }

    function enableButtons() {
        prevButton.prop("disabled", !prevEnabled());
        nextButton.prop("disabled", !nextEnabled());
    }

    function pageChange() {
        enableButtons();
        changePage(pages[pageSelector.value]);
    }

    $(pageSelector).change(pageChange);

    prevButton.click(function () {
        pageSelector.selectedIndex -= 1;
        pageChange();
    });

    nextButton.click(function () {
        pageSelector.selectedIndex += 1;
        pageChange();
    });

    $(document).keydown(function(event) {
        if(event.altKey === true) {
            if(event.which === 38) {
                // up arrow
                // prevent another simultaneous action if a control has focus
                event.preventDefault();
                if(prevEnabled()) {
                    pageSelector.selectedIndex -= 1;
                    pageChange();
                }
            } else if(event.which === 40) {
                // down arrow
                event.preventDefault();
                if(nextEnabled()) {
                    pageSelector.selectedIndex += 1;
                    pageChange();
                }
            }
        }
    });

    enableButtons();
    return [pageSelector, prevButton, nextButton];
}

function pageBrowse(container, params, storageKey, replaceUrl, mentorMode = false) {
    // If this function is used at the 'top level' params represents the
    // url. So when we change params we call the function replaceUrl so that
    // the url can be used as a link to the page shown. replaceUrl also
    // changes the browse history record so we must not call replaceUrl unless
    // we have actually changed params or the forward/back buttons will not
    // work as expected.

    // show error if ajax fails
    function showError(jqxhr) {
        alert(jqxhr.responseJSON.error);
    }

    let projectId = params.get("project");
    let displayMode = params.get("mode");
    if(!displayMode) {
        displayMode = "image";
    }
    let simpleHeader = params.get("simpleHeader");

    // showCurrentImageFile will be set to a function so that subsequent pages
    // can be shown without redrawing the whole page
    let showCurrentImageFile = null;
    let roundSelector = null;
    // this allows rounds to be obtained from server only once when needed
    // when roundSelector is built do callback
    // the roundSelector retains its selected item so we do not have to
    function getRoundSelector(callback) {
        if(roundSelector) {
            callback();
        } else {
            // if round_id is not defined or invalid, first option will be used
            let currentRound = params.get("round_id");
            roundSelector = document.createElement("select");
            roundSelector.title = proofIntData.strings.round;
            $.ajax(makeApiAjaxSettings("v1/projects/pagerounds"))
                .done(function(rounds) {
                    rounds.forEach(function(round) {
                        let selected = (currentRound === round);
                        roundSelector.add(new Option(round, round, selected, selected));
                    });
                    callback();
                })
                .fail(showError);
        }
    }

    function displayPages(pages) {
        let textButton = $("<input>", {type: 'button', value: proofIntData.strings.showText});
        let imageButton = $("<input>", {type: 'button', value: proofIntData.strings.showImage});
        let imageTextButton = $("<input>", {type: 'button', value: proofIntData.strings.showImageText});
        let imageWidget = null;
        let textWidget = null;

        function showCurrentPage(page) {
            // show a page selector and previous and next buttons and display the
            // page in current mode with controls

            function showImageText() {
                // show image and/or text for current page according to the
                // displayMode and set the url
                let imageFileName = page.image;
                params.set("imagefile", imageFileName);
                document.title = proofIntData.strings.displayPageX.replace("%s", imageFileName);
                if(displayMode !== "text") {
                    if(imageWidget) {
                        imageWidget.setImage(page.image_url);
                    }
                }
                if(displayMode !== "image") {
                    // if the supplied round_id was invalid it will be replaced
                    // by the shown (first) option
                    let round = roundSelector.value;
                    params.set("round_id", round);
                    $.ajax(makeApiAjaxSettings("v1/projects/" + projectId + "/pages/" + imageFileName + "/pagerounds/" + round))
                        .done(function(data) {
                            textWidget.setText(data.text);
                        })
                        .fail(showError);
                }
            }

            let pageControls = makePageControl(pages, page.image, function (newPage) {
                page = newPage;
                params.set("imagefile", page.image);
                replaceUrl();
                showImageText();
            });

            function showCurrentMode() {
                container.children().detach();

                function showTextModes() {
                    $(roundSelector).change( function() {
                        let round = roundSelector.value;
                        params.set("round_id", round);
                        replaceUrl();
                        showImageText();
                    });

                    let textDiv = $("<div>");
                    switch(displayMode) {
                    case "text": {
                        textWidget = makeTextWidget(textDiv, storageKey);
                        let controls = [imageButton, imageTextButton, " "].concat(pageControls, " ", roundSelector);
                        let content = $("<div>");
                        makeControlDiv(container, content, controls, storageKey);
                        content.append(textDiv);
                        break;
                    }
                    case "imageText": {
                        let imageDiv = $("<div>");
                        imageWidget = makeImageWidget(imageDiv, storageKey);

                        let topTextDiv = $("<div>");
                        textWidget = makeTextWidget(topTextDiv, storageKey, !mentorMode);
                        let bottomTextDiv = $("<div>");

                        textDiv.append(topTextDiv, bottomTextDiv);

                        let content = $("<div>");
                        content.append(imageDiv, textDiv);
                        let theSplitter = viewSplitter(content, storageKey);
                        let controls = [imageButton, textButton, " "].concat(pageControls, " ", roundSelector, theSplitter.buttons);
                        // the splitter must be redrawn when control bar is moved.
                        makeControlDiv(container, content, controls, storageKey, theSplitter.mainSplit.reLayout);

                        let storageKeySubSplit = storageKey + "-subsplit";
                        let subSplit = JSON.parse(localStorage.getItem(storageKeySubSplit));
                        if(!$.isPlainObject(subSplit)) {
                            subSplit = {splitPercent: 100};
                        }

                        let subSplitter = splitControl(textDiv, {splitVertical: false, splitPercent: subSplit.splitPercent, reDraw: theSplitter.mainSplit.reSize});
                        subSplitter.dragEnd.add(function (percent) {
                            subSplit.splitPercent = percent;
                            localStorage.setItem(storageKeySubSplit, JSON.stringify(subSplit));
                        });
                        break;
                    }
                    }
                    showImageText();
                }

                if(displayMode === "image") {
                    let controls;
                    if(simpleHeader) {
                        controls = pageControls;
                    } else {
                        controls = [textButton, imageTextButton, " "].concat(pageControls);
                    }
                    let imageDiv = $("<div>");
                    imageWidget = makeImageWidget(imageDiv, storageKey);
                    let content = $("<div>");
                    makeControlDiv(container, content, controls, storageKey);
                    content.append(imageDiv);
                    showImageText();
                } else {
                    // in case initial round_id was invalid, get round from
                    // round selector, but wait until it is drawn
                    getRoundSelector(showTextModes);
                }

            } // end of showCurrentMode

            function changeMode(mode) {
                displayMode = mode;
                params.set("mode", displayMode);
                replaceUrl();
                showCurrentMode();
            }

            textButton.click(function () {
                changeMode("text");
            });

            imageButton.click(function () {
                changeMode("image");
            });

            imageTextButton.click(function () {
                changeMode("imageText");
            });

            showCurrentMode();
        } // end of showCurrentPage

        function initialPageSelect() {
            document.title = proofIntData.strings.selectAPage;
            let initalPageControls = makePageControl(pages, null, function (page) {
                params.set("imagefile", page.image);
                replaceUrl();
                showCurrentPage(page);
            });
            let content = $("<div>");

            if(displayMode === "image") {
                makeControlDiv(container, content, initalPageControls, storageKey);
            } else {
                getRoundSelector(function () {
                    makeControlDiv(container, content, initalPageControls.concat(roundSelector), storageKey);
                });
            }
        }

        // if there are no pages in the project show alert message
        if(pages.length === 0) {
            alert(proofIntData.strings.noPages);
            return;
        }

        showCurrentImageFile = function(currentImageFileName) {
            if(currentImageFileName) {
                // does filename exist in the project?
                let currentPage = pages.find( function(page) {
                    return (currentImageFileName === page.image);
                });
                if(currentPage) {
                    showCurrentPage(currentPage);
                } else {
                    alert(proofIntData.strings.noPageX.replace("%s", currentImageFileName));
                    params.delete("imagefile");
                    replaceUrl();
                    initialPageSelect();
                }
            } else {
                initialPageSelect();
            }
        };
        showCurrentImageFile(params.get("imagefile"));
    } // end of displayPages

    $.ajax(makeApiAjaxSettings(`v1/projects/${projectId}/pages`)).done(displayPages)
        .fail(showError);

    // showCurrentImageFile will be null when we first get here
    // return a function to get its current value
    return function () {
        return showCurrentImageFile;
    };
}

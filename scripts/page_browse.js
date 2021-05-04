/*global $ proofIntData makeApiAjaxSettings splitControl makeImageWidget makeControlDiv */
/* exported pageBrowse */

// the controls are given class "control" so we can remove them from
// fixHead when changing modes while keeping project name and reset project

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
    fontFaceSelector.title = proofIntData.strings.changeFontFace;
    let fontSizeSelector = document.createElement("select");
    fontSizeSelector.title = proofIntData.strings.changeFontSize;

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

    let wrapControl = $("<label>", {class: "nowrap", text: proofIntData.strings.wrap}).append(wrapCheck);

    return [fontFaceSelector, fontSizeSelector, wrapControl];
};

function makeTextWidget(container, storageKey, splitter = false, reLayout = null) {
    let textWidgetKey = storageKey + "-textwidget";
    let textArea = $("<textarea>", {class: "text-pane"});
    textArea.prop("readonly", !splitter);
    let controls = maketextControl(textArea, textWidgetKey);
    let controlDiv = makeControlDiv(container, controls, textWidgetKey, reLayout);
    if(splitter) {
        let splitterKey = textWidgetKey + "-split";
        let textSplitData = JSON.parse(localStorage.getItem(splitterKey));
        if(!$.isPlainObject(textSplitData)) {
            textSplitData = {
                splitPercent: 100
            };
        }
        let topTextDiv = $("<div>").append(textArea);
        let bottomTextDiv = $("<div>");
        controlDiv.content.append(topTextDiv, bottomTextDiv);

        let subSplitter = splitControl(controlDiv.content, {splitVertical: false, splitPercent: textSplitData.splitPercent, reDraw: reLayout});
        subSplitter.dragEnd.add(function (percent) {
            textSplitData.splitPercent = percent;
            localStorage.setItem(splitterKey, JSON.stringify(textSplitData));
        });
    } else {
        controlDiv.content.append(textArea);
    }
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
    let vSwitchButton = $("<button>", {type: 'button', class: 'img-button control', title: proofIntData.strings.switchVert}).append(vSplitImage);
    let hSplitImage = $("<img>", {src: proofIntData.buttonImages.imgHSplit});
    let hSwitchButton = $("<button>", {type: 'button', class: 'img-button control', title: proofIntData.strings.switchHoriz}).append(hSplitImage);
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
    let controls = $("<span>", {class: "nowrap control"}).append(proofIntData.strings.page + " ", pageSelector);

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

        return controls;
    }

    pages.forEach(function(page, index) {
        let imageFilename = page.image;
        let selected = (selectedImageFileName === imageFilename);
        pageSelector.add(new Option(imageFilename, index, selected, selected));
    });

    let prevButton = $("<input>", {type: 'button', value: proofIntData.strings.previous});
    let nextButton = $("<input>", {type: 'button', value: proofIntData.strings.next});

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
    controls.append(prevButton, nextButton);
    return controls;
}

function pageBrowse(params, storageKey, replaceUrl, mentorMode = false) {
    // showCurrentImageFile will be set to a function so that subsequent pages
    // can be shown without redrawing the whole page
    let showCurrentImageFile = null;
    // parameters will be null if not defined
    let projectId = params.get("project");
    let displayMode = params.get("mode");
    if(!displayMode) {
        displayMode = "image";
    }
    // if round_id is not defined or invalid, first option will be used
    let currentRound = params.get("round_id");
    let simpleHeader = params.get("simpleHeader");
    // declare this here to avoid use before define warning
    let getProjectData;

    let topDiv = $("#page-browser");
    // the non-scrolling area which will contain the page controls
    let fixHead = $("<div>", {class: 'fixed-box control-pane'});
    // replace any previous content of topDiv
    topDiv.html(fixHead);

    // show error if ajax fails
    function showError(jqxhr) {
        alert(jqxhr.responseJSON.error);
    }

    let roundSelector = null;
    // this allows rounds to be obtained from server only once when needed
    // when roundSelector is built do callback
    // the roundSelector retains its selected item so we do not have to
    function getRoundSelector(callback) {
        if(roundSelector) {
            callback();
        } else {
            roundSelector = document.createElement("select");
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
        let textButton = $("<input>", {type: 'button', class: 'control', value: proofIntData.strings.showText});
        let imageButton = $("<input>", {type: 'button', class: 'control', value: proofIntData.strings.showImage});
        let imageTextButton = $("<input>", {type: 'button', class: 'control', value: proofIntData.strings.showImageText});
        let imageWidget = null;
        let textWidget = null;

        function getRoundControls() {
            return $("<span>", {class: "nowrap control"}).append(proofIntData.strings.round + " ", roundSelector);
        }

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
                replaceUrl();
            }

            let pageControls = makePageControl(pages, page.image, function (newPage) {
                page = newPage;
                showImageText();
            });

            function showCurrentMode() {
                // url with correct mode will be set in showImageText()
                params.set("mode", displayMode);

                // remove current image/text div if present
                // re-make the div here rather than making it higher up the chain
                // and emptying it here because the view splitter manipulates its
                // style causing side-effects if re-using it
                $(".imtext").remove();
                let stretchDiv = $("<div>", {class: 'imtext stretch-box'});
                topDiv.append(stretchDiv);
                // remove any old controls from fixHead
                $(".control", fixHead).detach();

                function showTextModes() {
                    let roundControls = getRoundControls();
                    $(roundSelector).change(showImageText);
                    let textDiv = $("<div>");
                    switch(displayMode) {
                    case "text":
                        textWidget = makeTextWidget(textDiv, storageKey);
                        fixHead.append(imageButton, imageTextButton, pageControls, roundControls);
                        stretchDiv.append(textDiv);
                        break;
                    case "imageText": {
                        let imageDiv = $("<div>");
                        imageWidget = makeImageWidget(imageDiv, storageKey);
                        stretchDiv.append(imageDiv, textDiv);
                        let theSplitter = viewSplitter(stretchDiv, storageKey);
                        if(mentorMode) {
                            // make a text widget with splitter
                            textWidget = makeTextWidget(textDiv, storageKey, true, theSplitter.mainSplit.reSize);
                        } else {
                            textWidget = makeTextWidget(textDiv, storageKey);
                        }
                        fixHead.append(imageButton, textButton, pageControls, roundControls, theSplitter.buttons);
                        break;
                    }
                    }
                    showImageText();
                }

                if(displayMode === "image") {
                    if(simpleHeader) {
                        fixHead.append(pageControls);
                    } else {
                        fixHead.append(textButton, imageTextButton, pageControls);
                    }

                    let imageDiv = $("<div>");
                    imageWidget = makeImageWidget(imageDiv, storageKey);
                    stretchDiv.append(imageDiv);
                    showImageText();
                } else {
                    // in case initial round_id was invalid, get round from
                    // round selector, but wait until it is drawn
                    getRoundSelector(showTextModes);
                }

            } // end of showCurrentMode

            textButton.click(function () {
                displayMode = "text";
                showCurrentMode();
            });

            imageButton.click(function () {
                displayMode = "image";
                showCurrentMode();
            });

            imageTextButton.click(function () {
                displayMode = "imageText";
                showCurrentMode();
            });

            showCurrentMode();
        } // end of showCurrentPage

        function initialPageSelect() {
            let initalPageControls = makePageControl(pages, null, function (page) {
                showCurrentPage(page);
            });
            fixHead.append(initalPageControls);
            if(displayMode !== "image") {
                getRoundSelector(function () {
                    fixHead.append(getRoundControls());
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

    function selectAProject() {
        params.delete("project");
        params.delete("imagefile");
        // keep mode and round
        replaceUrl();
        // just show the project input
        fixHead.empty();
        $(".imtext").remove();
        document.title = proofIntData.strings.browsePages;

        let projectSelectButton = $("<input>", {type: 'button', value: proofIntData.strings.selectProject});
        let projectInput = $("<input>", {type: 'text', required: true});

        projectSelectButton.click(function () {
            projectId = projectInput.val();
            if("" === projectId) {
                alert(proofIntData.strings.enterID);
                return;
            }
            params.set("project", projectId);
            replaceUrl();
            getProjectData();
        });

        fixHead.append($("<span>", {class: "nowrap"})
            .append(proofIntData.strings.projectid, " ", projectInput, projectSelectButton));
    }

    function showProjectInfo(projectData) {
        if(!simpleHeader) {
            fixHead.empty();
            // show project name and button to select another
            let resetButton = $("<input>", {type: 'button', value: proofIntData.strings.reset});
            resetButton.click(function () {
                selectAProject();
            });
            const projectRef = new URL(proofIntData.projectFile);
            projectRef.searchParams.append("id", projectId);
            fixHead.append($("<p>").append($("<a>", {href: projectRef}).append(projectData.title)), resetButton);
        }
        // get pages
        $.ajax(makeApiAjaxSettings(`v1/projects/${projectId}/pages`)).done(displayPages)
            .fail(showError);
    }

    getProjectData = function() {
        $.ajax(makeApiAjaxSettings("v1/projects/" + projectId)).done(showProjectInfo)
            .fail(function(jqxhr) {
                showError(jqxhr);
                selectAProject();
            });
    };

    if(projectId) {
        getProjectData();
    } else {
        selectAProject();
    }

    // showCurrentImageFile will be null when we first get here
    // return a function to get its current value
    return function () {
        return showCurrentImageFile;
    };
}

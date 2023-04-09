/*global $ proofIntData ajax splitControl makeImageWidget makeControlDiv */
/* exported pageBrowse */

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

function makeTextWidget(container, splitter = false, reLayout = null) {
    const textArea = $("<textarea>", {class: "text-pane"});
    textArea.prop("readonly", !splitter);
    const textControl = maketextControl(textArea);
    const content = $("<div>");
    const controlDiv = makeControlDiv(container, content, textControl.controls, reLayout);
    let subSplitter;
    let splitterKey;
    let textSplitData;
    if(splitter) {
        const topTextDiv = $("<div>", {class: "display-flex"}).append(textArea);
        const bottomTextDiv = $("<div>");
        content.append(topTextDiv, bottomTextDiv);

        subSplitter = splitControl(content, {splitVertical: false, reDraw: reLayout});
        subSplitter.dragEnd.add(function (percent) {
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
                if(!$.isPlainObject(textSplitData)) {
                    textSplitData = {splitPercent: 100};
                }
                subSplitter.setSplitPercent(textSplitData.splitPercent);
            }

            textControl.setup(textWidgetKey);
            controlDiv.setupControls(textWidgetKey);
        },

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

function makePageControl(pages, selectedImageFileName, changePage) {
    // changePage is a callback to act when page changes
    const pageSelector = document.createElement("select");
    const controls = $("<span>", {class: "nowrap control"}).append(proofIntData.strings.page + " ", pageSelector);

    if(!selectedImageFileName) {
        // when no page is defined, "Select a page" option is added
        pageSelector.required = true;
        const firstOption = new Option(proofIntData.strings.selectAPage, 0, true, true);
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
        const imageFilename = page.image;
        const selected = (selectedImageFileName === imageFilename);
        pageSelector.add(new Option(imageFilename, index, selected, selected));
    });

    const prevButton = $("<input>", {type: 'button', value: proofIntData.strings.previous});
    const nextButton = $("<input>", {type: 'button', value: proofIntData.strings.next});

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

function pageBrowse(params, storageKey, replaceUrl, mentorMode = false, setShowFile = function () {}) {
    // parameters will be null if not defined
    let projectId = params.get("project");
    let displayMode = params.get("mode");
    if(!['image', 'text', 'imageText'].includes(displayMode)) {
        displayMode = 'image';
    }
    // if round_id is not defined or invalid, first option will be used
    const currentRound = params.get("round_id");
    const simpleHeader = params.get("simpleHeader");
    // declare this here to avoid use before define warning
    let getProjectData;

    const topDiv = $("#page-browser");
    // the non-scrolling area which will contain the page controls
    const fixHead = $("<div>", {class: 'fixed-box control-pane'});
    // replace any previous content of topDiv
    topDiv.html(fixHead);

    let roundSelector = null;
    // this allows rounds to be obtained from server only once when needed
    // the roundSelector retains its selected item so we do not have to
    function getRoundSelector() {
        const gotRoundSelector = new Promise(function(resolve, reject) {
            if(roundSelector) {
                resolve();
            } else {
                roundSelector = document.createElement("select");
                ajax("GET", "v1/projects/pagerounds")
                    .then(function(rounds) {
                        rounds.forEach(function(round) {
                            let selected = (currentRound === round);
                            roundSelector.add(new Option(round, round, selected, selected));
                        });
                        resolve();
                    })
                    .catch(function(error) {
                        alert(error);
                        reject();
                    });
            }
        });
        return gotRoundSelector;
    }

    function displayPages(pages) {
        const textButton = $("<input>", {type: 'button', class: 'control', value: proofIntData.strings.showText});
        const imageButton = $("<input>", {type: 'button', class: 'control', value: proofIntData.strings.showImage});
        const imageTextButton = $("<input>", {type: 'button', class: 'control', value: proofIntData.strings.showImageText});
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
                const imageFileName = page.image;
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
                    const round = roundSelector.value;
                    params.set("round_id", round);
                    ajax("GET", `v1/projects/${projectId}/pages/${imageFileName}/pagerounds/${round}`)
                        .then(function(data) {
                            textWidget.setText(data.text);
                        }, alert);
                }
                replaceUrl();
            }

            const pageControls = makePageControl(pages, page.image, function (newPage) {
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
                const stretchDiv = $("<div>", {class: 'imtext stretch-box'});
                topDiv.append(stretchDiv);
                // remove any old controls from fixHead
                $(".control", fixHead).detach();

                if(displayMode === "image") {
                    if(simpleHeader) {
                        fixHead.append(pageControls);
                    } else {
                        fixHead.append(textButton, imageTextButton, pageControls);
                    }

                    const imageDiv = $("<div>");
                    imageWidget = makeImageWidget(imageDiv);
                    imageWidget.setup(storageKey);
                    stretchDiv.append(imageDiv);
                    showImageText();
                } else {
                    // in case initial round_id was invalid, get round from
                    // round selector, but wait until it is drawn
                    getRoundSelector().then(function showTextModes() {
                        const roundControls = getRoundControls();
                        $(roundSelector).change(showImageText);
                        const textDiv = $("<div>");
                        switch(displayMode) {
                        case "text":
                            textWidget = makeTextWidget(textDiv);
                            textWidget.setup(storageKey);
                            fixHead.append(imageButton, imageTextButton, pageControls, roundControls);
                            stretchDiv.append(textDiv);
                            break;
                        case "imageText": {
                            const imageDiv = $("<div>");
                            imageWidget = makeImageWidget(imageDiv);
                            stretchDiv.append(imageDiv, textDiv);
                            const theSplitter = viewSplitter(stretchDiv, storageKey);
                            if(mentorMode) {
                                // make a text widget with splitter
                                textWidget = makeTextWidget(textDiv, true, theSplitter.mainSplit.reSize);
                            } else {
                                textWidget = makeTextWidget(textDiv);
                            }
                            theSplitter.setSplitDirCallback.add(imageWidget.setup, textWidget.setup);
                            fixHead.append(imageButton, textButton, pageControls, roundControls, theSplitter.buttons);
                            theSplitter.fireSetSplitDir();
                            break;
                        }
                        }
                        showImageText();
                    });
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
            const initalPageControls = makePageControl(pages, null, function (page) {
                showCurrentPage(page);
            });
            fixHead.append(initalPageControls);
            if(displayMode !== "image") {
                getRoundSelector().then(function() {
                    fixHead.append(getRoundControls());
                });
            }
        }

        // if there are no pages in the project show alert message
        if(pages.length === 0) {
            alert(proofIntData.strings.noPages);
            return;
        }

        function showCurrentImageFile(currentImageFileName) {
            if(currentImageFileName) {
                // does filename exist in the project?
                const currentPage = pages.find( function(page) {
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
        }
        showCurrentImageFile(params.get("imagefile"));
        // showCurrentImageFile can be used to show subsequent images
        // without redrawing the whole page
        setShowFile(showCurrentImageFile);
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

        const projectSelectButton = $("<input>", {type: 'button', value: proofIntData.strings.selectProject});
        const projectInput = $("<input>", {type: 'text', required: true});

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

    function getPages() {
        ajax("GET", `v1/projects/${projectId}/pages`)
            .then(displayPages, alert);
    }

    function showProjectInfo(projectData) {
        fixHead.empty();
        // show project name and button to select another
        const resetButton = $("<input>", {type: 'button', value: proofIntData.strings.reset});
        resetButton.click(function () {
            selectAProject();
        });
        const projectRef = new URL(proofIntData.projectFile);
        projectRef.searchParams.append("id", projectId);
        fixHead.append($("<p>").append($("<a>", {href: projectRef}).append(projectData.title)), resetButton);
        getPages();
    }

    getProjectData = function() {
        ajax("GET", `v1/projects/${projectId}`)
            .then(showProjectInfo, function(error) {
                alert(error);
                selectAProject();
            });
    };

    if(projectId) {
        if(!simpleHeader) {
            getProjectData();
        } else {
            getPages();
        }
    } else {
        selectAProject();
    }
}

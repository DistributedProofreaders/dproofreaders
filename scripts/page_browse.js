/*global $ proofIntData makeApiAjaxSettings splitControl */
/* exported pageBrowse */

// the controls are given class "control" so we can remove them from
// fixHead when changing modes while keeping project name and reset project

// Construct the image sizing controls.
var imageControl = function(imageElement) {
    const imagePercentID = "image_percent";
    let percent = localStorage.getItem(imagePercentID);
    if(!percent) {
        percent = 100;
    }

    let percentInput = $("<input>", {type: 'number', min: '1', max: '999', value: percent});

    let setZoom = function () {
        imageElement.width(10 * percent);
        imageElement.height("auto");
    };

    percentInput.change(function() {
        percent = this.value;
        localStorage.setItem(imagePercentID, percent);
        setZoom();
    });

    setZoom();
    return $("<span>", {class: "nowrap control"}).append(proofIntData.strings.zoom + " ", percentInput, "% ");
};

// Construct the font-face, font-size and wrap controls
var textControl = function(textArea) {
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

    function setFontSize(fontSize) {
        let fontSizeCss = (fontSize === '') ? 'unset' : fontSize;
        textArea.css("font-size", fontSizeCss);
    }

    let currentFontSize = localStorage.getItem(fontSizeID);
    if(currentFontSize === null) {
        currentFontSize = '';
    }

    proofIntData.font.sizes.forEach(function(fontSize) {
        let selected = (currentFontSize === fontSize);
        let displayFontSize = (fontSize === '') ? proofIntData.strings.browserDefault : fontSize;
        fontSizeSelector.add(new Option(displayFontSize, fontSize, selected, selected));
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

    let wrapControl = $("<label>", {text: proofIntData.strings.wrap}).append(wrapCheck);

    return $("<span>", {class: "nowrap control"}).append(fontFaceSelector, fontSizeSelector, wrapControl);
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

function pageBrowse(params, replaceUrl, mentorMode = false) {
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
    // the non-scrolling area which will contain any error message and the form with controls
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
        let imageElement = $("<img>", {src: ''});
        let textArea = $("<textarea>", {class: "text-pane"});
        let textButton = $("<input>", {type: 'button', class: 'control', value: proofIntData.strings.showText});
        let imageButton = $("<input>", {type: 'button', class: 'control', value: proofIntData.strings.showImage});
        let imageTextButton = $("<input>", {type: 'button', class: 'control', value: proofIntData.strings.showImageText});

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
                    imageElement.attr("src", page.image_url).parent()
                        .scrollTop(0)
                        .scrollLeft(0);
                }
                if(displayMode !== "image") {
                    // if the supplied round_id was invalid it will be replaced
                    // by the shown (first) option
                    let round = roundSelector.value;
                    params.set("round_id", round);
                    $.ajax(makeApiAjaxSettings("v1/projects/" + projectId + "/pages/" + imageFileName + "/pagerounds/" + round))
                        .done(function(data) {
                            textArea.val(data.text)
                                .scrollTop(0)
                                .scrollLeft(0);
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

                const breakLine = "<br class='control'>";

                function showTextModes() {
                    textArea.prop("readonly", !mentorMode);
                    let roundControls = getRoundControls();
                    $(roundSelector).change(showImageText);
                    switch(displayMode) {
                    case "text":
                        fixHead.append(imageButton, imageTextButton, pageControls, roundControls, breakLine, textControl(textArea));
                        stretchDiv.append(textArea);
                        break;
                    case "imageText": {
                        let imageDiv = $("<div>", {class: 'overflow-auto'}).append(imageElement);
                        let textDiv = $("<div>").append(textArea);
                        if(mentorMode) {
                            let topTextDiv = textDiv;
                            let bottomTextDiv = $("<div>");
                            textDiv = $("<div>").append(topTextDiv, bottomTextDiv);
                        }
                        stretchDiv.append(imageDiv, textDiv);
                        let theSplitter = viewSplitter(stretchDiv);
                        if(mentorMode) {
                            const subSplitID = "sub_split_percent";
                            let subSplitPercent = parseFloat(localStorage.getItem(subSplitID));
                            if(!subSplitPercent) {
                                // if not defined or 0, set the value to 100 so the text is shown
                                subSplitPercent = 100;
                            }

                            let subSplitter = splitControl(textDiv,
                                {splitVertical: false, splitPercent: subSplitPercent, reDraw: theSplitter.mainSplit.reSize});
                            subSplitter.dragEnd.add(function (percent) {
                                localStorage.setItem(subSplitID, percent);
                            });
                        }
                        fixHead.append(imageButton, textButton, pageControls, roundControls, breakLine, imageControl(imageElement),
                            theSplitter.buttons, textControl(textArea));
                        break;
                    }
                    }
                    showImageText();
                }

                if(displayMode === "image") {
                    if(simpleHeader) {
                        fixHead.append(imageControl(imageElement), pageControls);
                    } else {
                        fixHead.append(textButton, imageTextButton, pageControls, breakLine, imageControl(imageElement));
                    }
                    stretchDiv.addClass("overflow-auto").append(imageElement);
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

/*global $ proofIntData ajax makeImageWidget makeTextWidget viewSplitter */
/* exported pageBrowse */

function makePageControl(pages, selectedImageFileName, changePage) {
    // changePage is a callback to act when page changes
    const pageSelector = document.createElement("select");
    const controls = $("<span>", { class: "nowrap" }).append(proofIntData.strings.page + " ", pageSelector);

    if (!selectedImageFileName) {
        // when no page is defined, "Select a page" option is added
        pageSelector.required = true;
        const firstOption = new Option(proofIntData.strings.selectAPage, 0, true, true);
        firstOption.disabled = true;
        pageSelector.add(firstOption);
        pages.forEach(function (page, index) {
            pageSelector.add(new Option(page.image, index, false, false));
        });

        pageSelector.addEventListener("change", function () {
            changePage(pages[pageSelector.value]);
        });

        return controls;
    }

    pages.forEach(function (page, index) {
        const imageFilename = page.image;
        const selected = selectedImageFileName === imageFilename;
        pageSelector.add(new Option(imageFilename, index, selected, selected));
    });

    const prevButton = $("<input>", { type: "button", value: proofIntData.strings.previous });
    const nextButton = $("<input>", { type: "button", value: proofIntData.strings.next });

    function prevEnabled() {
        return pageSelector.selectedIndex > 0;
    }

    function nextEnabled() {
        return pageSelector.selectedIndex < pageSelector.length - 1;
    }

    function enableButtons() {
        prevButton.prop("disabled", !prevEnabled());
        nextButton.prop("disabled", !nextEnabled());
    }

    function pageChange() {
        enableButtons();
        changePage(pages[pageSelector.value]);
    }

    pageSelector.addEventListener("change", pageChange);

    prevButton.click(function () {
        pageSelector.selectedIndex -= 1;
        pageChange();
    });

    nextButton.click(function () {
        pageSelector.selectedIndex += 1;
        pageChange();
    });

    document.addEventListener("keydown", function (event) {
        if (event.altKey === true) {
            if (event.key === "Up" || event.key === "ArrowUp") {
                // up arrow
                // prevent another simultaneous action if a control has focus
                event.preventDefault();
                if (prevEnabled()) {
                    pageSelector.selectedIndex -= 1;
                    pageChange();
                }
            } else if (event.key === "Down" || event.key === "ArrowDown") {
                // down arrow
                event.preventDefault();
                if (nextEnabled()) {
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
    if (!["image", "text", "imageText"].includes(displayMode)) {
        displayMode = "image";
    }
    // if round_id is not defined or invalid, first option will be used
    const currentRound = params.get("round_id");
    const simpleHeader = params.get("simpleHeader");
    // declare this here to avoid use before define warning
    let getProjectData;

    const topDiv = $("#page-browser");
    // the non-scrolling area which will contain the page controls
    const fixHead = $("<div>", { class: "fixed-box control-pane" });
    // replace any previous content of topDiv
    topDiv.html(fixHead);

    // controlSpan holds controls varying with mode
    const controlSpan = $("<span>");

    let roundSelector = null;
    // this allows rounds to be obtained from server only once when needed
    // the roundSelector retains its selected item so we do not have to
    async function populateRoundSelector() {
        if (roundSelector) {
            return;
        }
        try {
            const rounds = await ajax("GET", "v1/projects/pagerounds");
            roundSelector = document.createElement("select");
            rounds.forEach(function (round) {
                let selected = currentRound === round;
                roundSelector.add(new Option(round, round, selected, selected));
            });
        } catch (error) {
            alert(error.message);
        }
    }

    async function displayPages(pages) {
        const textButton = $("<input>", { type: "button", value: proofIntData.strings.showText });
        const imageButton = $("<input>", { type: "button", value: proofIntData.strings.showImage });
        const imageTextButton = $("<input>", { type: "button", value: proofIntData.strings.showImageText });
        let imageWidget = null;
        let textWidget = null;

        function getRoundControls() {
            return $("<span>", { class: "nowrap" }).append(proofIntData.strings.round + " ", roundSelector);
        }

        function showCurrentPage(page) {
            // show a page selector and previous and next buttons and display the
            // page in current mode with controls

            async function showImageText() {
                // show image and/or text for current page according to the
                // displayMode and set the url
                const imageFileName = page.image;
                params.set("imagefile", imageFileName);
                document.title = proofIntData.strings.displayPageX.replace("%s", imageFileName);
                if (displayMode !== "text") {
                    if (imageWidget) {
                        imageWidget.setImage(page.image_url);
                    }
                }
                if (displayMode !== "image") {
                    // if the supplied round_id was invalid it will be replaced
                    // by the shown (first) option
                    const round = roundSelector.value;
                    params.set("round_id", round);
                    try {
                        const data = await ajax("GET", `v1/projects/${projectId}/pages/${imageFileName}/pagerounds/${round}`);
                        textWidget.setText(data.text);
                    } catch (error) {
                        alert(error.message);
                    }
                }
                replaceUrl();
            }

            const pageControls = makePageControl(pages, page.image, function (newPage) {
                page = newPage;
                showImageText();
            });

            async function showCurrentMode() {
                // url with correct mode will be set in showImageText()
                params.set("mode", displayMode);

                // remove current image/text div if present
                // re-make the div here rather than making it higher up the chain
                // and emptying it here because the view splitter manipulates its
                // style causing side-effects if re-using it
                $(".imtext").remove();
                const stretchDiv = $("<div>", { class: "imtext stretch-box" });
                topDiv.append(stretchDiv);
                // remove any old controls from controlSpan
                controlSpan.children().detach();

                if (displayMode === "image") {
                    if (simpleHeader) {
                        controlSpan.append(pageControls);
                    } else {
                        controlSpan.append(textButton, imageTextButton, pageControls);
                    }

                    const imageDiv = $("<div>");
                    imageWidget = makeImageWidget(imageDiv);
                    imageWidget.setup(storageKey);
                    window.addEventListener("resize", imageWidget.reScroll);
                    stretchDiv.append(imageDiv);
                    showImageText();
                } else {
                    // in case initial round_id was invalid, get round from
                    // round selector, but wait until it is drawn
                    await populateRoundSelector();
                    const roundControls = getRoundControls();
                    $(roundSelector).change(showImageText);
                    const textDiv = $("<div>");
                    switch (displayMode) {
                        case "text":
                            textWidget = makeTextWidget(textDiv);
                            textWidget.setup(storageKey);
                            controlSpan.append(imageButton, imageTextButton, pageControls, roundControls);
                            stretchDiv.append(textDiv);
                            break;
                        case "imageText": {
                            const imageDiv = $("<div>");
                            imageWidget = makeImageWidget(imageDiv);
                            stretchDiv.append(imageDiv, textDiv);
                            const theSplitter = viewSplitter(stretchDiv, storageKey);
                            if (mentorMode) {
                                // make a text widget with splitter
                                textWidget = makeTextWidget(textDiv, true);
                                theSplitter.mainSplit.onResize.add(textWidget.reLayout);
                            } else {
                                textWidget = makeTextWidget(textDiv);
                            }
                            theSplitter.mainSplit.onResize.add(imageWidget.reScroll);
                            theSplitter.preSetSplitDirCallback.push(imageWidget.setup, textWidget.setup);
                            theSplitter.postSetSplitDirCallback.add(imageWidget.initAll);
                            controlSpan.append(imageButton, textButton, pageControls, roundControls, theSplitter.button);
                            theSplitter.fireSetSplitDir();
                            break;
                        }
                    }
                    showImageText();
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

        async function initialPageSelect() {
            controlSpan.empty();
            const initialPageControls = makePageControl(pages, null, function (page) {
                showCurrentPage(page);
            });
            controlSpan.append(initialPageControls);
            if (displayMode !== "image") {
                await populateRoundSelector();
                controlSpan.append(getRoundControls());
            }
        }

        // if there are no pages in the project show alert message
        if (pages.length === 0) {
            alert(proofIntData.strings.noPages);
            return;
        }

        async function showCurrentImageFile(currentImageFileName) {
            if (currentImageFileName) {
                // does filename exist in the project?
                const currentPage = pages.find(function (page) {
                    return currentImageFileName === page.image;
                });
                if (currentPage) {
                    showCurrentPage(currentPage);
                } else {
                    alert(proofIntData.strings.noPageX.replace("%s", currentImageFileName));
                    params.delete("imagefile");
                    replaceUrl();
                    await initialPageSelect();
                }
            } else {
                await initialPageSelect();
            }
        }

        await showCurrentImageFile(params.get("imagefile"));
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

        const projectSelectButton = $("<input>", { type: "button", value: proofIntData.strings.selectProject });
        const projectInput = $("<input>", { type: "text", required: true });

        projectSelectButton.click(function () {
            projectId = projectInput.val();
            if ("" === projectId) {
                alert(proofIntData.strings.enterID);
                return;
            }
            params.set("project", projectId);
            replaceUrl();
            getProjectData();
        });

        fixHead.append($("<span>", { class: "nowrap" }).append(proofIntData.strings.projectid, " ", projectInput, projectSelectButton));
    }

    async function getPages() {
        fixHead.append(controlSpan);
        try {
            const pages = await ajax("GET", `v1/projects/${projectId}/pages`);
            displayPages(pages);
        } catch (error) {
            alert(error.message);
        }
    }

    function showProjectTitle(projectData) {
        fixHead.empty();
        // show project name and button to select another
        const resetButton = $("<input>", { type: "button", value: proofIntData.strings.reset });
        resetButton.click(function () {
            selectAProject();
        });
        const projectRef = new URL(proofIntData.projectFile);
        projectRef.searchParams.append("id", projectId);
        fixHead.append($("<p>").append($("<a>", { href: projectRef }).append(projectData.title)), resetButton);
        getPages();
    }

    getProjectData = async function () {
        try {
            const projectData = await ajax("GET", `v1/projects/${projectId}`, { field: "title" });
            showProjectTitle(projectData);
        } catch (error) {
            alert(error.message);
            selectAProject();
        }
    };

    if (projectId) {
        if (!simpleHeader) {
            getProjectData();
        } else {
            getPages();
        }
    } else {
        selectAProject();
    }
}

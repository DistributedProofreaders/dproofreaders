/*global makeUrl codeUrl actionButton makeLabel hide show */

import translate from "./gettext.js";
import { ajax } from "./api.js";
import { viewSplitter } from "./view_splitter.js";
import { makeImageWidget } from "./image_widget.js";
import { makeTextWidget } from "./text_widget.js";

function makePageControl(pages, selectedImageFileName, changePage) {
    // changePage is a callback to act when page changes

    const pageSelector = document.createElement("select");
    const pageSelectorLabel = makeLabel([translate.gettext("Page") + ": ", pageSelector]);

    const prevButton = actionButton(translate.gettext("Previous"));
    const nextButton = actionButton(translate.gettext("Next"));

    pages.forEach(function (page, index) {
        const imageFilename = page.image;
        const selected = selectedImageFileName === imageFilename;
        pageSelector.add(new Option(imageFilename, index, selected, selected));
    });

    function prevEnabled() {
        return pageSelector.selectedIndex > 0;
    }

    function nextEnabled() {
        return pageSelector.selectedIndex < pageSelector.length - 1;
    }

    function enableButtons() {
        prevButton.disabled = !prevEnabled();
        nextButton.disabled = !nextEnabled();
    }

    function pageChange() {
        enableButtons();
        changePage(pages[pageSelector.value]);
    }

    pageSelector.addEventListener("change", pageChange);

    prevButton.addEventListener("click", function () {
        pageSelector.selectedIndex -= 1;
        pageChange();
    });

    nextButton.addEventListener("click", function () {
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
    return [pageSelectorLabel, prevButton, nextButton];
}

export async function pageBrowse(container, params, replaceUrl, setShowFile = function () {}) {
    // clear anything in the container
    container.innerHTML = "";

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

    const titlePara = document.createElement("p");
    const projectTitleSpan = document.createElement("span");
    const projectLink = document.createElement("a");
    projectLink.textContent = translate.gettext("Project Page");
    titlePara.append(projectTitleSpan, " ", projectLink);

    const pageControlDiv = document.createElement("div");
    pageControlDiv.classList.add("fixed-box", "top_settings_box");

    const imageTextDiv = document.createElement("div");
    imageTextDiv.classList.add("stretch-box");

    if (!simpleHeader) {
        container.append(titlePara);
    }
    container.append(pageControlDiv, imageTextDiv);

    const textButton = actionButton(translate.gettext("Show Text only"));
    const imageButton = actionButton(translate.gettext("Show Image only"));
    const imageTextButton = actionButton(translate.gettext("Show Image & Text"));
    const resetButton = actionButton(translate.gettext("Select a different project"));

    let imageWidget = null;
    let textWidget = null;
    let page = null;
    let roundSelector = null;

    async function showCurrentPage() {
        // show image and/or text for current page according to the
        // displayMode and set the url
        const imageFileName = page.image;
        params.set("imagefile", imageFileName);
        replaceUrl();
        document.title = translate.gettext("Display Page: %s").replace("%s", imageFileName);

        if (displayMode !== "text") {
            imageWidget.setImage(page.image_url);
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
    }

    let pages;

    function findPage(imageFile) {
        return pages.find(function (page) {
            return imageFile === page.image;
        });
    }

    function showImageFile(imageFile) {
        page = findPage(imageFile);
        showCurrentPage();
    }

    setShowFile(showImageFile);

    let roundControl;
    // this allows rounds to be obtained from server only once when needed
    // the roundSelector retains its selected item so we do not have to
    async function populateRoundSelector() {
        if (roundSelector) {
            return;
        }
        try {
            const rounds = await ajax("GET", "v1/projects/pagerounds");
            roundSelector = document.createElement("select");
            roundControl = makeLabel([translate.gettext("Round") + ": ", roundSelector]);

            rounds.forEach(function (round) {
                let selected = currentRound === round;
                roundSelector.add(new Option(round, round, selected, selected));
            });
            roundSelector.addEventListener("change", showCurrentPage);
        } catch (error) {
            alert(error.message);
        }
    }

    let pageControls;
    let userSettings;

    async function showCurrentMode() {
        let textDiv, imageDiv;
        // the view splitter manipulates its style causing side-effects
        // clear out any contents and style
        imageTextDiv.innerHTML = "";
        imageTextDiv.style = "";

        if (displayMode !== "image") {
            await populateRoundSelector();
            textDiv = document.createElement("div");
            textDiv.classList.add("column-flex");
            textWidget = makeTextWidget(textDiv, userSettings);
        }
        if (displayMode !== "text") {
            imageDiv = document.createElement("div");
            imageDiv.classList.add("column-flex");
            imageWidget = makeImageWidget(imageDiv, userSettings);
        }
        pageControlDiv.innerHTML = "";
        switch (displayMode) {
            case "image":
                if (simpleHeader) {
                    pageControlDiv.append(...pageControls);
                } else {
                    pageControlDiv.append(resetButton, textButton, imageTextButton, ...pageControls);
                }
                imageTextDiv.append(imageDiv);
                break;
            case "text":
                pageControlDiv.append(resetButton, imageButton, imageTextButton, ...pageControls, roundControl);
                imageTextDiv.append(textDiv);
                break;
            case "imageText": {
                imageTextDiv.append(imageDiv, textDiv);
                const theSplitter = viewSplitter(imageTextDiv, userSettings);
                theSplitter.mainSplit.onResize.add(textWidget.reLayout);
                theSplitter.mainSplit.onResize.add(imageWidget.reSize);
                theSplitter.setSplitDirCallback.push(textWidget.setup, imageWidget.reset);
                pageControlDiv.append(resetButton, imageButton, textButton, ...pageControls, roundControl, ...theSplitter.buttons);
                theSplitter.fireSetSplitDir();
                break;
            }
        }
        showCurrentPage();
    }

    textButton.addEventListener("click", async () => {
        displayMode = "text";
        await showCurrentMode();
    });

    imageButton.addEventListener("click", async () => {
        displayMode = "image";
        await showCurrentMode();
    });

    imageTextButton.addEventListener("click", async () => {
        displayMode = "imageText";
        await showCurrentMode();
    });

    async function displayPages() {
        // is a page given?
        const imageFile = params.get("imagefile");
        if (!imageFile) {
            // display first page
            page = pages[0];
        } else {
            page = findPage(imageFile);
            if (!page) {
                alert(translate.gettext("There is no page %s in this project").replace("%s", imageFile));
                page = pages[0];
            }
        }
        pageControls = makePageControl(pages, imageFile, function (newPage) {
            page = newPage;
            showCurrentPage();
        });

        showCurrentMode();
    }

    function selectAProject() {
        params.delete("project");
        params.delete("imagefile");
        // keep mode and round
        replaceUrl();
        document.title = translate.gettext("Browse pages");
        // just show the project input
        projectTitleSpan.textContent = translate.gettext("Browse pages");
        hide(projectLink);
        pageControlDiv.innerHTML = "";
        imageTextDiv.innerHTML = "";

        const projectInput = document.createElement("input");
        projectInput.type = "text";
        const projectSelectButton = actionButton(translate.gettext("Select Project"));
        const projectInputLabel = makeLabel([translate.gettext("Project ID") + ": ", projectInput]);
        pageControlDiv.append(projectInputLabel, projectSelectButton);

        projectSelectButton.addEventListener("click", function () {
            projectId = projectInput.value;
            if ("" === projectId) {
                alert(translate.gettext("Please enter a project ID"));
                return;
            }
            params.set("project", projectId);
            replaceUrl();
            getProjectData();
        });
    }

    resetButton.addEventListener("click", selectAProject);

    async function getPages() {
        try {
            pages = await ajax("GET", `v1/projects/${projectId}/pages`);
            await displayPages();
        } catch (error) {
            alert(error.message);
        }
    }

    function showProjectTitle(projectData) {
        projectLink.href = makeUrl(`${codeUrl}/project.php`, { id: projectId });
        show(projectLink);
        projectTitleSpan.textContent = projectData.title;
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

    try {
        userSettings = await ajax("GET", `v1/storage/newpi`);
        if (projectId) {
            if (!simpleHeader) {
                getProjectData();
            } else {
                getPages();
            }
        } else {
            selectAProject();
        }
    } catch (error) {
        alert(error.message);
    }
}

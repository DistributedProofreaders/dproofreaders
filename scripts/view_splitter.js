/* global codeUrl */
import translate from "./gettext.js";
import { splitControl } from "./splitControl.js";

// Construct the button for horizontal/vertical split
// and return a splitter variable.
export var viewSplitter = function (container, storageKey) {
    const storageKeyLayout = storageKey + "-layout";
    let layout = JSON.parse(localStorage.getItem(storageKeyLayout));
    if (!layout || (layout.splitDirection !== "horizontal" && layout.splitDirection !== "vertical")) {
        layout = { splitDirection: "horizontal" };
    }
    let splitVertical = layout.splitDirection === "vertical";

    const mainSplit = splitControl(container, { splitVertical: splitVertical });

    const splitButton = document.createElement("button");
    splitButton.type = "button";
    splitButton.innerHTML = "&nbsp;";
    let splitKey;
    const preSetSplitDirCallback = [];
    const postSetSplitDirCallback = new Set();

    preSetSplitDirCallback.push(function (storageKey) {
        // get the split percent for vertical or horizontal
        splitKey = storageKey + "-split";
        let directionData = JSON.parse(localStorage.getItem(splitKey));
        if (!directionData || (typeof directionData.splitPercent !== "number" && typeof directionData.splitPercent !== "string")) {
            directionData = { splitPercent: 50 };
        }
        mainSplit.setSplitPercent(directionData.splitPercent);
    });

    const iconFolder = `${codeUrl}/pinc/3rdparty/iconify`;

    function setSplitControls() {
        splitButton.style.backgroundImage = splitVertical ? `url("${iconFolder}/octicon-rows-24.svg")` : `url("${iconFolder}/octicon-columns-24.svg")`;
        splitButton.title = splitVertical ? translate.gettext("Change to horizontal layout") : translate.gettext("Change to vertical layout");
    }

    function fireSetSplitDir() {
        preSetSplitDirCallback.forEach(function (preSetSplitDirCallbackFunction) {
            preSetSplitDirCallbackFunction(storageKey + "-" + layout.splitDirection);
        });
        mainSplit.reLayout();
        postSetSplitDirCallback.forEach(function (postSetSplitDirCallbackFunction) {
            postSetSplitDirCallbackFunction();
        });
    }

    splitButton.addEventListener("click", function () {
        splitVertical = !splitVertical;
        layout.splitDirection = splitVertical ? "vertical" : "horizontal";
        localStorage.setItem(storageKeyLayout, JSON.stringify(layout));
        mainSplit.setSplit(splitVertical);
        setSplitControls();
        fireSetSplitDir();
    });

    setSplitControls();

    mainSplit.onDragEnd.add(function (percent) {
        localStorage.setItem(splitKey, JSON.stringify({ splitPercent: percent }));
    });

    return {
        mainSplit,
        button: splitButton,
        preSetSplitDirCallback,
        postSetSplitDirCallback,
        fireSetSplitDir,
        resize: mainSplit.reLayout,
    };
};

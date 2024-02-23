/* global splitControl proofIntData codeUrl */
/* exported viewSplitter */

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

    const splitButton = document.createElement('button');
    splitButton.type = 'button';
    splitButton.innerHTML = "&nbsp;";
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

    const iconFolder = `${codeUrl}/pinc/3rdparty/iconify/octicon`;

    function setSplitControls() {
        splitButton.style.backgroundImage = splitVertical ? `url("${iconFolder}/rows-24.svg")` : `url("${iconFolder}/columns-24.svg")`;
        splitButton.title = splitVertical ? proofIntData.strings.layoutHorizontal : proofIntData.strings.layoutVertical;
    }

    function fireSetSplitDir() {
        setSplitDirCallback.forEach(function (setSplitDirCallbackFunction) {
            setSplitDirCallbackFunction(storageKey + "-" + layout.splitDirection);
        });
        mainSplit.reLayout();
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
        localStorage.setItem(splitKey, JSON.stringify({splitPercent: percent}));
    });

    return {
        mainSplit,
        button: splitButton,
        setSplitDirCallback,
        fireSetSplitDir,
    };
};

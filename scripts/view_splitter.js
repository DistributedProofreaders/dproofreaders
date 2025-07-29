/* global codeUrl */
import translate from "./gettext.js";
import { splitControl } from "./splitControl.js";

export var viewSplitter = function (container, userSettings) {
    // userSettings.splitVertical ??= true; if poss.
    userSettings.splitVertical ?? (userSettings.splitVertical = true);

    function getPercentKey() {
        return `${userSettings.splitVertical ? "v" : "h"}percent`;
    }

    function getPercent() {
        return userSettings[getPercentKey()] ?? 50;
    }

    const mainSplit = splitControl(container, { splitVertical: userSettings.splitVertical, splitPercent: getPercent() });
    window.addEventListener("resize", mainSplit.reLayout);

    function imageButton(title, icon) {
        const iconFolder = `${codeUrl}/pinc/3rdparty/iconify`;
        const iButton = document.createElement("img");
        iButton.src = `${iconFolder}/${icon}`;
        iButton.title = title;
        iButton.classList.add("image_button");
        return iButton;
    }

    const splitVerticalButton = imageButton(translate.gettext("Change to vertical layout"), "codicon--split-horizontal.svg");
    const splitHorizontalButton = imageButton(translate.gettext("Change to horizontal layout"), "codicon--split-vertical.svg");

    const setSplitDirCallback = [];

    setSplitDirCallback.push(function () {
        mainSplit.setSplitPercent(getPercent());
    });

    function fireSetSplitDir() {
        setSplitDirCallback.forEach(function (setSplitDirCallbackFunction) {
            setSplitDirCallbackFunction(userSettings.splitVertical);
        });
        mainSplit.reLayout();
    }

    function setSplitControls() {
        const [activeButton, inactiveButton] = userSettings.splitVertical
            ? [splitHorizontalButton, splitVerticalButton]
            : [splitVerticalButton, splitHorizontalButton];
        activeButton.disabled = false;
        activeButton.classList.remove("opaque");
        inactiveButton.disabled = true;
        inactiveButton.classList.add("opaque");
    }

    function setSplitDirection(splitV) {
        userSettings.splitVertical = splitV;
        setSplitControls();
        mainSplit.setSplit(userSettings.splitVertical);
        fireSetSplitDir();
    }

    splitVerticalButton.addEventListener("click", function () {
        setSplitDirection(true);
    });

    splitHorizontalButton.addEventListener("click", function () {
        setSplitDirection(false);
    });

    setSplitControls();

    mainSplit.onDragEnd.add(function (percent) {
        userSettings[getPercentKey()] = percent;
    });

    return {
        mainSplit,
        setSplitDirCallback,
        fireSetSplitDir,
        buttons: [splitVerticalButton, splitHorizontalButton],
    };
};

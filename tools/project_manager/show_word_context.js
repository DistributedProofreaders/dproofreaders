/*global $ splitControl pageBrowse showWordContext proofIntData */

$(function () {
    let storageKeyLayout = showWordContext.storageKey + "-layout";
    let layout;
    try {
        layout = JSON.parse(localStorage.getItem(storageKeyLayout));
    } catch(error) {
        layout = null;
    }
    if(!$.isPlainObject(layout)) {
        layout = {splitPercent: 30, splitDirection: "horizontal"};
    }
    let splitVertical = (layout.splitDirection === "vertical");

    function saveLayout() {
        localStorage.setItem(storageKeyLayout, JSON.stringify(layout));
    }

    // this is a function to get a function to show a file
    let getShowCurrentImageFile = null;
    let switchLink = $("#h_v_switch");

    let mainSplit = splitControl("#show_word_context_container", {
        splitVertical: splitVertical,
        splitPercent: layout.splitPercent
    });
    mainSplit.reLayout();

    function setSplitLink() {
        switchLink.text(splitVertical ? proofIntData.strings.layoutHorizontal : proofIntData.strings.layoutVertical);
    }

    setSplitLink();

    switchLink.click(function () {
        splitVertical = !splitVertical;
        mainSplit.setSplit(splitVertical);
        setSplitLink();
        layout.splitDirection = splitVertical ? "vertical" : "horizontal";
        saveLayout();
    });

    mainSplit.dragEnd.add(function (percent) {
        layout.splitPercent = percent;
        saveLayout();
    });

    let params = new URLSearchParams();
    params.set("mode", "image");
    params.set("project", showWordContext.projectid);
    params.set("simpleHeader", "true");

    $(".page-select").click( function () {
        let imageFile = this.dataset.value;
        let ShowCurrentImageFile;
        // getShowCurrentImageFile will be null the first time
        if(getShowCurrentImageFile && (ShowCurrentImageFile = getShowCurrentImageFile())) {
            // ShowCurrentImageFile could be null if ajax failed or slow
            ShowCurrentImageFile(imageFile);
        } else {
            params.set("imagefile", imageFile);
            // give a no-action function for replace params
            getShowCurrentImageFile = pageBrowse($("#page-browser"), params, showWordContext.storageKey, function () {});
        }
    });
});

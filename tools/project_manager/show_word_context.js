/*global $ splitControl pageBrowse showWordContext proofIntData */

$(function () {
    let STORAGE_KEY_PERCENT = 'show_word_context_percent';
    // this is a function to get a function to show a file
    let getShowCurrentImageFile = null;
    let switchLink = $("#h_v_switch");

    let splitPercent = parseFloat(localStorage.getItem(STORAGE_KEY_PERCENT));
    if(!splitPercent) {
        // if not defined or 0, set the value to 30%.
        splitPercent = 30;
    }

    // stored value is "horizontal" or "vertical"
    let splitDirString = localStorage.getItem(showWordContext.storageKeyLayout);
    // splitVertical is true or false
    let splitVertical = false;

    if(splitDirString) {
        splitVertical = (splitDirString === "vertical");
    }

    let mainSplit = splitControl("#show_word_context_container", {
        splitVertical: splitVertical,
        splitPercent: splitPercent,
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
        localStorage.setItem(showWordContext.storageKeyLayout, splitVertical ? "vertical" : "horizontal");
    });

    mainSplit.dragEnd.add(function (percent) {
        localStorage.setItem(STORAGE_KEY_PERCENT, percent);
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
            getShowCurrentImageFile = pageBrowse(params, function () {});
        }
    });
});

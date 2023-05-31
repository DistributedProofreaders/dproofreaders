/*global splitControl pageBrowse showWordContext proofIntData */

window.addEventListener("DOMContentLoaded", function() {
    let storageKeyLayout = showWordContext.storageKey + "-layout";
    let layout;
    try {
        layout = JSON.parse(localStorage.getItem(storageKeyLayout));
    } catch(error) {
        layout = null;
    }
    if(!layout || !layout.splitDirection || !layout.splitPercent) {
        layout = {splitPercent: 30, splitDirection: "horizontal"};
    }
    let splitVertical = (layout.splitDirection === "vertical");

    function saveLayout() {
        localStorage.setItem(storageKeyLayout, JSON.stringify(layout));
    }

    let switchLink = document.getElementById("h_v_switch");

    let mainSplit = splitControl("#show_word_context_container", {
        splitVertical: splitVertical,
        splitPercent: layout.splitPercent
    });
    mainSplit.reLayout();

    function setSplitLink() {
        switchLink.textContent = splitVertical ? proofIntData.strings.layoutHorizontal : proofIntData.strings.layoutVertical;
    }

    setSplitLink();

    switchLink.addEventListener("click", function () {
        splitVertical = !splitVertical;
        mainSplit.setSplit(splitVertical);
        setSplitLink();
        layout.splitDirection = splitVertical ? "vertical" : "horizontal";
        saveLayout();
    });

    mainSplit.onDragEnd.add(function (percent) {
        layout.splitPercent = percent;
        saveLayout();
    });

    let params = new URLSearchParams();
    params.set("mode", "image");
    params.set("project", showWordContext.projectid);
    params.set("simpleHeader", "true");

    let ShowImageFile = null;
    function setShowImageFile(showFile) {
        ShowImageFile = showFile;
    }

    function showImage(imageFile) {
        if (!ShowImageFile) {
            params.set("imagefile", imageFile);
            pageBrowse(params, showWordContext.storageKey, function () {}, false, setShowImageFile);
        } else {
            ShowImageFile(imageFile);
        }
    }

    document.querySelectorAll(".page-select").forEach(pageSelect => {
        pageSelect.addEventListener("click", function () {
            showImage(this.dataset.value);
        });
    });
});

/*global splitControl pageBrowse showWordContext */

window.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("page-browser");
    const widgetText = JSON.parse(container.dataset.widget_text);

    let storageKeyLayout = showWordContext.storageKey + "-layout";
    let layout;
    try {
        layout = JSON.parse(localStorage.getItem(storageKeyLayout));
    } catch {
        layout = null;
    }
    if (!layout || !layout.splitDirection || !layout.splitPercent) {
        layout = { splitPercent: 30, splitDirection: "horizontal" };
    }
    let splitVertical = layout.splitDirection === "vertical";

    function saveLayout() {
        localStorage.setItem(storageKeyLayout, JSON.stringify(layout));
    }

    let switchLink = document.getElementById("h_v_switch");

    let mainSplit = splitControl(document.getElementById("show_word_context_container"), {
        splitVertical: splitVertical,
        splitPercent: layout.splitPercent,
    });
    window.addEventListener("resize", mainSplit.reLayout);
    mainSplit.reLayout();

    function setSplitLink() {
        switchLink.textContent = splitVertical ? widgetText.splitHorizontal : widgetText.splitVertical;
    }

    setSplitLink();

    switchLink.addEventListener("click", function () {
        splitVertical = !splitVertical;
        mainSplit.setSplit(splitVertical);
        mainSplit.reLayout();
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
            pageBrowse(container, params, function () {}, setShowImageFile);
        } else {
            ShowImageFile(imageFile);
        }
    }

    document.querySelectorAll(".page-select").forEach((pageSelect) => {
        pageSelect.addEventListener("click", function () {
            showImage(this.dataset.value);
        });
    });

    const wordInstancesSelect = document.getElementById("wordInstancesSelect");
    if (wordInstancesSelect) {
        wordInstancesSelect.addEventListener("change", function () {
            this.form.submit();
        });
    }
});

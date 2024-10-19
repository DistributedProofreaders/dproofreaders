window.addEventListener("DOMContentLoaded", function () {
    function checkAllVisible() {
        const table = document.getElementById(`table_${this.dataset.instance}`);
        const tableBodies = table.getElementsByTagName("tbody");
        for (const tableBody of tableBodies) {
            if (tableBody.style.display !== "none") {
                const checkBoxes = tableBody.getElementsByClassName("checkbox");
                for (const checkBox of checkBoxes) {
                    checkBox.checked = true;
                }
            }
        }
    }

    const checkAllVisLinks = document.getElementsByClassName("check_all");
    for (const checkAllVisLink of checkAllVisLinks) {
        checkAllVisLink.addEventListener("click", checkAllVisible);
    }

    function unCheckAll() {
        const table = document.getElementById(`table_${this.dataset.instance}`);
        const checkBoxes = table.getElementsByClassName("checkbox");
        for (const checkBox of checkBoxes) {
            checkBox.checked = false;
        }
    }

    const unCheckAllLinks = document.getElementsByClassName("un_check_all");
    for (const unCheckAllLink of unCheckAllLinks) {
        unCheckAllLink.addEventListener("click", unCheckAll);
    }

    function setCutoff() {
        const newCutoff = Number(this.dataset.cutoff);
        const tableBodies = document.getElementsByTagName("tbody");
        for (const tableBody of tableBodies) {
            if (Number(tableBody.dataset.freqCutoff) < newCutoff) {
                tableBody.style.display = "none";
            } else {
                tableBody.style.display = "";
            }
        }
        document.getElementById("current_cutoff").innerHTML = newCutoff;
        // persist cutoff after submit for show_good_word_suggestions.php
        if (document.getElementById("freqCutoffValue")) {
            document.getElementById("freqCutoffValue").value = newCutoff;
        }
    }

    const cutoffLinks = document.getElementsByClassName("cut_off_link");
    for (const cutoffLink of cutoffLinks) {
        cutoffLink.addEventListener("click", setCutoff);
    }
});

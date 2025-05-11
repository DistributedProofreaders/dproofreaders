window.addEventListener("DOMContentLoaded", () => {
    let creditNameSelector = document.getElementById("credit_name");
    let creditOtherInput = document.getElementById("credit_other");

    function maybeShowOther() {
        creditOtherInput.hidden = creditNameSelector.value !== "other";
    }

    if (creditNameSelector) {
        creditNameSelector.addEventListener("change", maybeShowOther);
        maybeShowOther();
    }

    const checkAllButton = document.getElementById("check_all");
    if (checkAllButton) {
        checkAllButton.addEventListener("click", function () {
            for (const creditBox of document.getElementsByClassName("credit_box")) {
                creditBox.checked = true;
            }
        });
    }

    const unCheckAllButton = document.getElementById("un_check_all");
    if (unCheckAllButton) {
        unCheckAllButton.addEventListener("click", function () {
            for (const creditBox of document.getElementsByClassName("credit_box")) {
                creditBox.checked = false;
            }
        });
    }
});

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
});

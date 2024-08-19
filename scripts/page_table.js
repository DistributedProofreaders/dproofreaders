window.addEventListener("DOMContentLoaded", () => {
    let optionSelector = document.getElementById("option_selector");

    function changeSelection(option) {
        switch (option) {
            case "all":
                for (let box of document.getElementsByClassName("selectorbox")) {
                    box.checked = true;
                }
                break;
            case "clear":
                for (let box of document.getElementsByClassName("selectorbox")) {
                    box.checked = false;
                }
                break;
            case "unproofed":
                changeSelection("clear");
                for (let box of document.getElementsByClassName("availbox")) {
                    box.checked = true;
                }
                break;
            case "invert":
                for (let box of document.getElementsByClassName("selectorbox")) {
                    box.checked = !box.checked;
                }
                break;
        }
    }

    if (optionSelector) {
        optionSelector.addEventListener("change", function () {
            changeSelection(this.value);
        });
    }
});

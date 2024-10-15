"use strict";
window.addEventListener("DOMContentLoaded", () => {
    /*
     * When the user clicks on a button,
     * toggle between hiding and showing the dropdown content
     * if off close all the others before turning on
     */

    function closeAll() {
        const dropdowns = document.getElementsByClassName("dropdown-content");
        for (let openDropdown of dropdowns) {
            openDropdown.classList.remove("dropdown-show");
        }
    }

    function toggleList() {
        const thisDropdown = this.nextElementSibling;
        const turnOn = !thisDropdown.classList.contains("dropdown-show");
        closeAll();
        if (turnOn) {
            thisDropdown.classList.add("dropdown-show");
        }
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function (event) {
        if (!event.target.matches(".dropdown-button")) {
            closeAll();
        }
    };

    window.onkeydown = function (event) {
        if (event.code === "Escape") {
            closeAll();
        }
    };

    let linkButtons = document.getElementsByClassName("dropdown-button");
    for (let linkButton of linkButtons) {
        linkButton.addEventListener("click", toggleList);
    }
});

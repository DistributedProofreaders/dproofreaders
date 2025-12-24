/* global popHelpData */

document.addEventListener("DOMContentLoaded", function () {
    const pophelp = document.createElement("div");
    pophelp.className = "pophelp";
    document.body.appendChild(pophelp);

    document.querySelectorAll("[data-pophelp]").forEach((elem) => {
        elem.addEventListener("mouseenter", function () {
            let content = popHelpData[this.dataset.pophelp]["content"];
            pophelp.innerHTML = content;
            const rect = this.getBoundingClientRect();
            pophelp.style.left = rect.left + window.scrollX + "px";
            pophelp.style.top = rect.bottom + window.scrollY + "px";
            pophelp.style.display = "block";
        });

        elem.addEventListener("mouseleave", function () {
            pophelp.style.display = "none";
        });
    });
});

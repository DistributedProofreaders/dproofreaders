/*global validateText */

window.addEventListener("DOMContentLoaded", () => {
    const updateTextButton = document.getElementById("update_text");
    if (updateTextButton) {
        updateTextButton.addEventListener("click", function(event) {
            if(!validateText()) {
                event.preventDefault();
            }
        });
    }
});

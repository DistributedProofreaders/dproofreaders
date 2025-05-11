window.addEventListener("DOMContentLoaded", function () {
    const meTooButton = document.getElementById("me_too_button");
    const meTooMainDiv = document.getElementById("MeTooMain");
    const cancelMeTooButton = document.getElementById("cancel_me_too");

    if (meTooButton) {
        meTooButton.addEventListener("click", function () {
            meTooButton.style.display = "none";
            meTooMainDiv.style.display = "";
        });
    }

    if (cancelMeTooButton) {
        cancelMeTooButton.addEventListener("click", function () {
            meTooMainDiv.style.display = "none";
            meTooButton.style.display = "";
        });
    }
});

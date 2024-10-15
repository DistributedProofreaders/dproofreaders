window.addEventListener("DOMContentLoaded", function () {
    const changeStateForms = document.getElementsByClassName("frm_chg_stt");

    function confirmSubmit(event) {
        if (this.dataset.confirmationQuestion && !confirm(this.dataset.confirmationQuestion)) {
            event.preventDefault();
        }
    }

    for (const changeStateForm of changeStateForms) {
        changeStateForm.addEventListener("submit", confirmSubmit);
    }

    const newTopicLink = document.getElementById("no_topic_yet");

    function disableLink() {
        this.outerHTML = this.firstChild.nodeValue;
    }

    if (newTopicLink) {
        // prevent clicking again if there is a delay in creating the topic
        newTopicLink.addEventListener("click", disableLink);
    }
});

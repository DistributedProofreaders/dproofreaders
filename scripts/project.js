window.addEventListener("DOMContentLoaded", function () {
    function confirmSubmit(event) {
        if (this.dataset.confirmationQuestion && !confirm(this.dataset.confirmationQuestion)) {
            event.preventDefault();
        }
    }

    const changeStateForms = document.getElementsByClassName("frm_chg_stt");
    for (const changeStateForm of changeStateForms) {
        changeStateForm.addEventListener("submit", confirmSubmit);
    }

    function disableLink() {
        this.outerHTML = this.firstChild.nodeValue;
    }

    const newTopicLink = document.getElementById("no_topic_yet");
    if (newTopicLink) {
        // prevent clicking again if there is a delay in creating the topic
        newTopicLink.addEventListener("click", disableLink);
    }
});

import { pageBrowse } from "../scripts/page_browse.js";

window.addEventListener("DOMContentLoaded", function () {
    let url = new URL(window.location.href);

    function replaceUrl() {
        window.history.replaceState(null, "", url.href);
    }

    const container = document.getElementById("page_browser");
    pageBrowse(container, url.searchParams, replaceUrl);
});

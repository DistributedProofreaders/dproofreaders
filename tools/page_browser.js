/*global mentorMode */
import { pageBrowse } from "../scripts/page_browse.js";

window.addEventListener("DOMContentLoaded", function () {
    let url = new URL(window.location.href);

    function replaceUrl() {
        window.history.replaceState(null, "", url.href);
    }

    const pageBrowser = pageBrowse(url.searchParams, "page-browse", replaceUrl, mentorMode);

    window.addEventListener("resize", pageBrowser.resize);
});

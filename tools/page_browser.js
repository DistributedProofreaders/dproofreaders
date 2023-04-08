/*global pageBrowse mentorMode */

window.addEventListener("DOMContentLoaded", function() {
    let url = new URL(window.location.href);

    function replaceUrl() {
        window.history.replaceState(null, '', url.href);
    }

    pageBrowse(url.searchParams, "page-browse", replaceUrl, mentorMode);
});

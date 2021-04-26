/*global $ pageBrowse mentorMode */

$(function () {
    let url = new URL(window.location.href);

    function replaceUrl() {
        window.history.replaceState(null, '', url.href);
    }

    pageBrowse(url.searchParams, replaceUrl, mentorMode);
});

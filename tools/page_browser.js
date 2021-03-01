/*global $ pageBrowse mentorMode */

$(function () {
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);

    function replaceUrl() {
        url.search = params.toString();
        window.history.replaceState(null, '', url.href);
    }

    pageBrowse(params, replaceUrl, mentorMode);
});

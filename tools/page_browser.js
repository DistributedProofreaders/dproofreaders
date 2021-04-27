/*global pageBrowse mentorMode */

window.onload = function () {
    // this function is not called when fwd/back button is pressed
    let url = new URL(window.location.href);

    function replaceUrl() {
        window.history.pushState(null, '', url.href);
    }

    pageBrowse(url.searchParams, replaceUrl, mentorMode);

    window.onpopstate = function() {
        // this is called "each time the active history entry changes between
        // two history entries for the same document"
        url.href = window.location.href;
        pageBrowse(url.searchParams, replaceUrl, mentorMode);
    };
};

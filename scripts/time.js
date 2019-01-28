/*global $ apiUrl*/
$(function () {
    "use strict";
    var timeSpan = $("#server-time");

    function showTime(data) {
        timeSpan.text(data.daytime);
    }

    function updateClock() {
        $.getJSON(apiUrl, {'q': 'v1/serverdaytime'}, showTime);
    }

    setInterval(updateClock, 60000);
    updateClock();
});

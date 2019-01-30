/*global $ apiUrl setInterval*/
$(function () {
    "use strict";
    var timeSpan = $("#server-time");
    var day = "";
    var msec0 = Date.now();

    function pad2(n) {
        return ("0" + n).slice(-2);
    }

    function showTime(hour, minute) {
        timeSpan.text(day + " " + pad2(hour) + ":" + pad2(minute));
    }

    function resetTime(data) {
        var hour = data.hour;
        var minute = data.minute;
        day = data.day;
        // milliseconds from midnight
        var msecs = 60000 * (parseInt(minute) + 60 * parseInt(hour));
        // milliseconds from epoch to previous midnight
        msec0 = Date.now() - msecs;
        showTime(hour, minute);
    }

    function getServerTime() {
        $.getJSON(apiUrl, {'q': 'v1/serverdaytime'}, resetTime);
    }

    function calcTime() {
        var minutes = Math.round((Date.now() - msec0) / 60000);
        if (minutes >= 1440) {
            getServerTime();
        } else {
            showTime(Math.floor(minutes / 60), minutes % 60);
        }
    }

    setInterval(calcTime, 60000);
    getServerTime();
});

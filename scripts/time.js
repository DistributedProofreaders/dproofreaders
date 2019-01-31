/*global $ setInterval moment serverTimezone*/
$(function () {
    "use strict";

    var timeSpan = $("#server-time");

    function showTime() {
        timeSpan.text(moment.tz(serverTimezone).format("ddd HH:mm"));
    }

    setInterval(showTime, 60000);
    showTime();
});

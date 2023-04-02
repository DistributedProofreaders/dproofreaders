/*global moment serverTimezone*/

window.addEventListener("DOMContentLoaded", () => {
    "use strict";

    var timeSpan = document.getElementById("server-time");

    function showTime() {
        timeSpan.textContent = moment.tz(serverTimezone).format("ddd HH:mm");
    }

    setInterval(showTime, 60000);
    showTime();
});

/*global moment serverTimezone*/

window.addEventListener("DOMContentLoaded", () => {
    "use strict";

    const timeSpan = document.getElementById("server-time");
    const dateTimeFormat = new Intl.DateTimeFormat('en-US', {
        dateStyle: 'medium',
        timeStyle: 'short',
        timeZone: 'UTC',
        hourCycle: 'h24',
      });

    function showTime() {
        timeSpan.textContent = dateTimeFormat.format(Date.now());
    }

    setInterval(showTime, 60000);
    showTime();
});

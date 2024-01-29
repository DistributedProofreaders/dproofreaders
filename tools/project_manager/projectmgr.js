/*global serverTimezone userLanguage*/

window.addEventListener("DOMContentLoaded", () => {
    "use strict";

    const timeSpan = document.getElementById("server-time");
    const dateTimeFormat = new Intl.DateTimeFormat(userLanguage, {
        timeZone: serverTimezone,
        hourCycle: 'h24',
        hour: '2-digit',
        minute: '2-digit',
        weekday: 'short'
    });

    function showTime() {
        timeSpan.textContent = dateTimeFormat.format(Date.now());
    }

    setInterval(showTime, 60000);
    showTime();
});

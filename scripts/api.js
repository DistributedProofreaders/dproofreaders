/*global $ alert console*/

$(document).ajaxError(function (event, jqxhr, settings, thrownError) {
    alert("HTTP status: " + jqxhr.status + "\n" + jqxhr.responseJSON.error);
});

$(document).ajaxSuccess(function (event, jqXHR, ajaxOptions, data) {
    if (data.log) {
        console.log(data.log);
    }
});
/*global $ uploadMessages */
$(function() {

    function showProgress(text) {
        $("#upload_progress").html(text);
    }

    // Before we start the upload, prevent the user from hitting upload again.
    $("#old_submit").click(function() {
        // won't work if we disable browse button
        $("#old_submit").prop( "disabled", true);
        showProgress(uploadMessages.working);
    });
});

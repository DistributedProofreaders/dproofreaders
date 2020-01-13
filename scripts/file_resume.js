/*global $ Resumable uploadTarget uploadMessages */
$(function() {
    var resumable = new Resumable({
        target: uploadTarget,
        testTarget: uploadTarget,
        forceChunkSize: true,
        maxFiles: 1,
        fileType: ['zip'], // use extension not mime type since zips have many
        maxFileSize: 1024 * 1024 * 1024  // 1GB
    });

    function showProgress(text) {
        $("#upload_progress").html(text);
    }

    if(resumable.support) {
//    if(false) {
        resumable.assignBrowse($("#resumable_browse"));
//        resumable.assignDrop($(upload));

        // Show the resumable div
        $("#resumable_uploader").show();
        // Hide the old uploader
        $("#old_uploader").hide();
    }

    // Before we start the upload, prevent the user from hitting upload again.
    $("#old_submit").click(function() {
        // won't work if we disable browse button
        $("#old_submit").prop( "disabled", true);
        showProgress(uploadMessages.working);
    });

    $("#resumable_submit").click(function() {
        // in this case we can prevent the user from selecting another file
        $("#resumable_browse").prop( "disabled", true);
        $("#resumable_submit").prop( "disabled", true);
        resumable.upload();
    });

    // After a file has been selected, display its name
    resumable.on('fileAdded', function(file) {
        $("#resumable_selected_file").text(file.fileName);
    });

    // After a file has been successfully uploaded, we update the form
    // and submit it for final validation (AV scan, etc).
    resumable.on('fileSuccess', function(file) {
        $('input[name="resumable_filename"]').val(file.fileName);
        $('input[name="mode"]').val("resumable");
        showProgress(uploadMessages.finalizingUpload);
        $("#upload_form").submit();
    });

    // If an error occured, re-enable the upload form and show a message
    resumable.on('fileError', function(file, message) {
        $("#resumable_browse").show();
        $("#resumable_submit").show();
        showProgress(uploadMessages.uploadFailed + "<br>" + message);
    });

    // As the file upload progresses, show a percentage complete
    resumable.on('progress', function() {
        showProgress( (resumable.progress() * 100).toFixed(2) + '%');
    });
});

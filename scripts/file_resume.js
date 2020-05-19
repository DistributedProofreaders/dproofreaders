/*global $ Resumable uploadTarget uploadMessages maxResumeSize maxNormSize */
$(function() {

    // This function has a server-side pair in pinc/upload_file.inc:is_valid_filename()
    // which should be updated if the below logic changes.
    function validate(name) {
        // the name must contain only a-z,A-Z,0-9,-,_ and not start with -
        // and end with .zip (length is enforced a few lines down)
        var validChars = /^\w[\w-]*\.\w+$/;
        if(!validChars.test(name)) {
            alert(uploadMessages.invalidChars + ": '" + name + "'");
            return false;
        }
        var zipTest = /^.+\.zip$/;
        if(!zipTest.test(name)) {
            alert(uploadMessages.mustBeZip);
            return false;
        }
        if(name.length > 200) {
            alert(uploadMessages.nameTooLong);
            return false;
        }
        return true;
    }

    var resumable = new Resumable({
        target: uploadTarget,
        testTarget: uploadTarget,
        forceChunkSize: true,
        maxFiles: 1,
        fileType: ['zip'], // use extension not mime type since zips have many
        maxFileSize: maxResumeSize,
    });

    function showProgress(text) {
        $("#upload_progress").html(text);
    }

    if(resumable.support) {
        resumable.assignBrowse($("#resumable_browse"));
        resumable.assignDrop($("#upload_form"));

        // Show the resumable div
        $("#resumable_uploader").show();
        // Hide the old uploader
        $("#old_uploader").hide();
    }

    // Before we start the upload, prevent the user from hitting upload again.
    $("#old_submit").click(function(ev) {
        let file = $("#old_browse")[0].files[0];
        if(file.size >= maxNormSize) {
            alert(uploadMessages.fileTooBig);
            ev.preventDefault();
            return false;
        }
        if(validate(file.name)) {
            // won't work if we disable browse button so hide it
            $("#old_browse").hide();
            $("#old_submit").prop( "disabled", true);
            showProgress(uploadMessages.working);
        } else {
            ev.preventDefault();
        }
    });

    $("#resumable_submit").click(function() {
        // in this case we can prevent the user from selecting another file
        $("#resumable_browse").prop( "disabled", true);
        $("#resumable_submit").prop( "disabled", true);
        if(resumable.files.length) {
            // a file has been selected
            resumable.upload();
        } else {
            alert(uploadMessages.noFile);
        }
    });

    // After a file has been selected, display its name
    resumable.on('fileAdded', function(file) {
        let filename = file.fileName;
        if(validate(filename)) {
            $("#resumable_selected_file").text(filename);
        } else {
            // if the validation failed, remove it from the list
            resumable.removeFile(file);
        }
    });

    // After a file has been successfully uploaded, we update the form
    // and submit it for final validation (AV scan, etc).
    resumable.on('fileSuccess', function(file) {
        $('input[name="resumable_filename"]').val(file.fileName);
        $('input[name="resumable_identifier"]').val(file.uniqueIdentifier);
        $('input[name="mode"]').val("resumable");
        showProgress(uploadMessages.finalizingUpload);
        $("#upload_form").submit();
    });

    // If an error occurred, re-enable the upload form and show a message
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

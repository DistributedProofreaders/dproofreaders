/*global Resumable uploadTarget uploadMessages maxResumeSize maxNormSize */
window.addEventListener("DOMContentLoaded", function() {

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

    // polyfill for file.arrayBuffer()
    function fileToArrayBuffer(file) {
        if ('arrayBuffer' in file) {
            return file.arrayBuffer();
        }
        return new Promise (function (resolve, reject) {
            const reader = new FileReader();
            reader.addEventListener("loadend", function() {
                resolve(reader.result);
            });
            reader.addEventListener("error", function() {
                reject();
            });
            reader.readAsArrayBuffer(file);
        });
    }

    // polyfill for String.padStart(2, "0")
    function pad2(s) {
        return (s.length < 2) ? `0${s}` : s;
    }

    function fileHash(file) {
        return fileToArrayBuffer(file)
            .then(function (arrayBuffer) {
                return crypto.subtle.digest("SHA-256", arrayBuffer);
            })
            .then(function(hashAsArrayBuffer) {
                const uint8ViewOfHash = new Uint8Array(hashAsArrayBuffer);
                const hashAsString = Array.from(uint8ViewOfHash)
                    .map((b) => pad2(b.toString(16)))
                    .join("");
                return hashAsString;
            });
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
        document.getElementById("upload_progress").innerHTML = text;
    }

    function submitWithHash(hashString) {
        document.getElementById("hash_code").value = hashString;
        document.getElementById("upload_form").submit();
    }

    if(resumable.support) {
        resumable.assignBrowse([document.getElementById("resumable_browse")]);
        resumable.assignDrop([document.getElementById("upload_form")]);

        // Show the resumable div
        this.document.getElementById("resumable_uploader").style.display = "";
        // Hide the old uploader
        document.getElementById("old_uploader").style.display = "none";
    }

    // Before we start the upload, prevent the user from hitting upload again.
    document.getElementById("old_submit").addEventListener("click", function(ev) {
        let file = document.getElementById("old_browse").files[0];
        if(file.size >= maxNormSize) {
            alert(uploadMessages.fileTooBig);
            ev.preventDefault();
            return false;
        }
        if(validate(file.name)) {
            // won't work if we disable the buttons so hide them
            document.getElementById("old_browse").style.display = "none";
            document.getElementById("old_submit").style.display = "none";
            showProgress(uploadMessages.working);
            ev.preventDefault();
            fileHash(file)
                .then(submitWithHash);
        } else {
            ev.preventDefault();
        }
    });

    document.getElementById("resumable_submit").addEventListener("click", function() {
        // in this case we can prevent the user from selecting another file
        document.getElementById("resumable_browse").disabled = true;
        document.getElementById("resumable_submit").disabled = true;
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
            document.getElementById("resumable_selected_file").textContent = filename;
        } else {
            // if the validation failed, remove it from the list
            resumable.removeFile(file);
        }
    });

    // After a file has been successfully uploaded, we update the form
    // and submit it for final validation (AV scan, etc).
    resumable.on('fileSuccess', function(file) {
        document.querySelector('input[name="resumable_filename"]').value = file.fileName;
        document.querySelector('input[name="resumable_identifier"]').value = file.uniqueIdentifier;
        document.querySelector('input[name="mode"]').value = "resumable";
        showProgress(uploadMessages.finalizingUpload);
        fileHash(file.file)
            .then(submitWithHash);
    });

    // If an error occurred, re-enable the upload form and show a message
    resumable.on('fileError', function(file, message) {
        document.getElementById("resumable_browse").style.display = '';
        document.getElementById("resumable_submit").style.display = '';
        showProgress(uploadMessages.uploadFailed + "<br>" + message);
    });

    // As the file upload progresses, show a percentage complete
    resumable.on('progress', function() {
        showProgress( (resumable.progress() * 100).toFixed(2) + '%');
    });
});

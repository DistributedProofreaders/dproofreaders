<?php

class FileException extends Exception {}

$big_upload_blurb = sprintf(_("If you are trying to upload a very big zip file
    and the upload does not succeed, upload a small placeholder zip file
    instead and email %s for assistance."), "<span class='nowrap'>$db_requests_email_addr</span>");

$upload_messages = json_encode([
    'working' => _("Working, please wait..."),
    'uploadFailed' => _("Upload failed"),
    'finalizingUpload' => _("Upload complete. Running file checks, please wait..."),
]);

$resumable_upload_size = 1024 * 1024 * 1024;  // 1GB

function show_upload_form($form_content, $submit_label)
{
    global $big_upload_blurb, $resumable_upload_size;

    $max_upload_size = humanize_bytes(return_bytes(ini_get("upload_max_filesize")));
    $standard_blurb = _("<b>Note:</b> Please make sure the file you upload is Zipped (not Gzip, TAR, etc.). The file should have the .zip extension, NOT .Zip, .ZIP, etc.")
        . "<br>" . _("The rest of the file's name must consist of ASCII letters, digits, underscores, and/or hyphens. It must not begin with a hyphen.");

    $submit_blurb = "<p>" . sprintf(_("After you click the '%s' button, the browser will appear to be slow getting to the next page. This is because it is uploading the file."), $submit_label) . "</p>";

    // Display a div with a form; some content which can be abritrary HTML/other inputs;
    // a 'choose file' button and a labeled submit button
    echo "<div style='border: 1px solid grey; margin-left: .5em; padding: .25em;'>\n";

    echo "<form id='upload_form' method='POST' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='mode' value='upload'>";
    echo $form_content;

    echo "<p>$standard_blurb</p>";
    // these are the form elements for non-resumable upload
    echo "<div id='old_uploader'>
    <p class='warning'>", sprintf(_('Maximum file size is %s.'), $max_upload_size), "</p>
    <p>$big_upload_blurb</p>",
    _("File to upload"), ": <input type='file' id='old_browse' accept='.zip' name='uploaded_file' size='25' maxsize='50'>
    <input type='submit' id='old_submit' value='", attr_safe($submit_label), "'>",
    "<p>", sprintf(_("After you click the '%s' button, the browser will appear to be slow getting to the next page. This is because it is uploading the file."), $submit_label), "</p>",
    "</div>";

    // if resumable, these are shown instead
    echo "<div id='resumable_uploader' style='display: none;'>
    <input type='hidden' name='resumable_filename' value=''>
    <p>", sprintf(_("Your browser supports uploading large files (up to %s) via javascript. If the upload fails,
        or you navigate away from this page before it finishes, uploading the file again will pick up where it left off."), humanize_bytes($resumable_upload_size)), "</p>
    <p>", _("File to upload"), ": <span id='resumable_selected_file'></span> &nbsp;
    <span id='resumable_browse' class='button'>", _("Choose File"), "</span>
    <span id='resumable_submit' class='button'>", _("Upload"), "</span></p>
    </div>";

    echo "<p>", _("Upload progress"), ":&nbsp;<span id='upload_progress'></span></p>";

    echo "</form>\n";
    echo "</div>\n";
}

function validate_uploaded_file()
{
    // return a valid file (or null) or throw a FileException
    try
    {
        $mode = get_enumerated_param($_REQUEST, 'mode', 'upload', ['upload', 'resumable']);
        if('upload' == $mode)
        {
            $file_info = $_FILES['uploaded_file'];

            // If there is no file uploaded, that might be OK so just return.
            if (is_null($file_info) || $file_info['name'] == '') {
                return NULL;
            }
            $original_name = $file_info['name'];
            $file_path = $file_info['tmp_name'];
            $error = $file_info['error'];
            $file_size = $file_info['size'];

            if ($error != UPLOAD_ERR_OK) {
                throw new FileException(get_upload_err_msg($error));
            }
            // there must be only one file.
            $file_count = count($original_name);
            if ($file_count > 1) {
                throw new FileException( _("You may only upload one file") );
            }
            // allow anti_virus to read it
            chmod($file_path, 0644);
        }
        else
        {
            // resumable upload
            $root_staging_dir = "/tmp/resumable_uploads";
            $original_name = array_get($_POST, "resumable_filename", "");
            $hashed_filename = md5($original_name);
            $file_path = "$root_staging_dir/$hashed_filename";
            $file_size = filesize($file_path);
        }
        // do some checks.
        empty_check($file_size);
        zip_check($original_name, $file_path);
        virus_check($file_path);
        return $file_path;
    }
    catch(FileException $e)
    {
        if(is_file($file_path))
        {
            unlink($file_path);
        }
        throw($e);
    }
}

function empty_check($file_size)
{
    if (0 == $file_size) {
        throw new FileException(_("File is empty"));
    }
}

function zip_check($original_name, $file_path)
{
    if (substr($original_name, -4) != ".zip")
    {
        throw new FileException( _("File name extension must be '.zip'") );
    }
    // ensure that it's a valid zip
    // The extension was already checked and the file is not properly named (it has some temporary name), so we should
    // disable the extension check.
    if (!is_valid_zip_file($file_path, true))
    {
        throw new FileException( _("Not a valid zip file") );
    }
}

function virus_check($file_path)
{
    global $antivirus_executable;
    if($antivirus_executable)
    {
        // perform '$antivirus_executable -- <FILENAME>' and expect return
        // value = 0. we use -- to not parse any further arguments starting
        // with -/-- as options
        $av_test_result = array();
        $av_retval=0;

        $cmd = "$antivirus_executable -- " . escapeshellcmd($file_path);
        exec($cmd, $av_test_result, $av_retval);
        // $av_retval == 0 is ok
        if ($av_retval == 1)
        {
            // Log the infected upload so that we can track user/frequency
            $reporting_string = "Infected upload: " . $av_test_result[0];
            error_log($reporting_string);
            throw new FileException(sprintf(_("AntiVirus: The scan reported an infection: %s. The upload has been discarded.
                You should perform a complete virus scan on your computer as soon as possible."), $av_test_result[0]));
        }
        else if($av_retval > 1)
        {
            throw new FileException(_("AntiVirus error:") . $av_retval . "<br>" . $av_test_result[0]);
        }
    }
}

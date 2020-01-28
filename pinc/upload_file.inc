<?php
include_once($relPath.'misc.inc'); // get_upload_err_msg(), attr_safe(), return_bytes()

// This contains functions for uploading files using a resumable process if
// supported by the browser. The process takes place in two stages: Showing
// an upload form, then processing the uploaded data.
// It is assumed that large uploads will be needed only with zipped files, then
// the function show_upload_form() can be used. This posts the data to the same
// php page where the function validate_uploaded_file() can be used.
// If you need to upload only small files your form can contain an
// <input type='file'> with name='uploaded_file' Then validate_uploaded_file()
// can be used to process the upload.

define("RESUMABLE_UPLOAD_SIZE", 1024 * 1024 * 1024);  // 1GB

// If uploading large files this function should be called near the beginning
// of the page to which the upload is submitted (resumable or not). It detects
// a problem which can occur with non-resumable uploads when the file uploaded
// was larger than post_max_size and shows an error instead of failing silently.
// We do this first because if the POST failed, $_REQUEST and $_POST are empty
// and we have no data.
// http://andrewcurioso.com/blog/archive/2010/detecting-file-size-overflow-in-php.html
function detect_too_large()
{
    if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) &&
        empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0)
    {
        die( sprintf(_("Uploaded file is too large. Maximum file size is %s."), get_max_upload_size()));
    }
}

function get_max_upload_size()
{
    return humanize_bytes(return_bytes(ini_get("upload_max_filesize")));
}

function get_big_upload_blurb()
{
    global $db_requests_email_addr;

    return sprintf(_("If you are trying to upload a very big zip file
        and the upload does not succeed, upload a small placeholder zip file
        instead and email %s for assistance."), "<span class='nowrap'>$db_requests_email_addr</span>");
}

// This supplies the aruments for the header in the page containing the upload
// form below
function get_upload_args()
{
    global $code_url, $utf8_site;

    $upload_messages = [
        'working' => _("Working, please wait..."),
        'uploadFailed' => _("Upload failed"),
        'finalizingUpload' => _("Upload complete. Running file checks, please wait..."),
    ];
    if(!$utf8_site)
    {
        array_walk($upload_messages, function(&$item) { $item = utf8_encode($item);} );
    }
    $upload_messages = json_encode($upload_messages);

    return [
        'js_files' => [
            "$code_url/pinc/3rdparty/resumablejs/resumable.js",
            "$code_url/scripts/file_resume.js",
        ],
        'js_data' => "
            var uploadTarget = '$code_url/tools/upload_resumable_file.php';
            var uploadMessages = $upload_messages;
            var maxSize = " . RESUMABLE_UPLOAD_SIZE . ";",
    ];
}

// This shows a form with a border for uploading a file using
// a resumable process if supported by the browser.
// The form is submitted to the php file in which it is used.
// Extra content can be supplied in the $form_content parameter
// this can be abritrary HTML/other inputs
// and can contain a hidden input which can be used to
// decide when to process the uploaded file using the validate_uploaded_file()
// function below. Be careful to avoid $form_content clashing with
// the names or ids used internally in this form.
// It contains a 'choose file' button and a labeled submit button
// and shows approprite messages to the user.
function show_upload_form($form_content, $submit_label)
{
    $standard_blurb = _("<b>Note:</b> Please make sure the file you upload is Zipped (not Gzip, TAR, etc.). The file should have the .zip extension, NOT .Zip, .ZIP, etc.")
        . "<br>" . _("The rest of the file's name must consist of ASCII letters, digits, underscores, and/or hyphens. It must not begin with a hyphen and be no longer than 200 characters.");

    $submit_blurb = "<p>" . sprintf(_("After you click the '%s' button, the browser will appear to be slow getting to the next page. This is because it is uploading the file."), $submit_label) . "</p>";

    echo "<div style='border: 1px solid grey; margin-left: .5em; padding: .25em;'>\n";

    echo "<form id='upload_form' method='POST' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='mode' value='upload'>";
    echo $form_content;

    echo "<p>$standard_blurb</p>";
    // these are the form elements for non-resumable upload
    echo "<div id='old_uploader'>
    <p class='warning'>", sprintf(_('Maximum file size is %s.'), get_max_upload_size()), "</p>
    <p>", get_big_upload_blurb(), "</p>",
    _("File to upload"), ": <input type='file' id='old_browse' accept='.zip' name='uploaded_file'>
    <input type='submit' id='old_submit' value='", attr_safe($submit_label), "'>",
    "<p>", sprintf(_("After you click the '%s' button, the browser will appear to be slow getting to the next page. This is because it is uploading the file."), $submit_label), "</p>",
    "</div>";

    // if resumable, these are shown instead
    echo "<div id='resumable_uploader' style='display: none;'>
    <input type='hidden' name='resumable_filename' value=''>
    <input type='hidden' name='resumable_identifier' value=''>
    <p>", sprintf(_("Your browser supports uploading large files (up to %s) via javascript. If the upload fails,
        or you navigate away from this page before it finishes, uploading the file again will pick up where it left off."), humanize_bytes(RESUMABLE_UPLOAD_SIZE)), "</p>
    <p>", _("File to upload"), ": <span id='resumable_selected_file'></span> &nbsp;
    <span id='resumable_browse' class='button'>", _("Choose File"), "</span>
    <span id='resumable_submit' class='button'>", _("Upload"), "</span></p>
    </div>";

    echo "<p>", _("Upload progress"), ":&nbsp;<span id='upload_progress'></span></p>";

    echo "</form>\n";
    echo "</div>\n";
}

// This exception is thrown if problems are found in validate_uploaded_file()
// or the other file-check functions below. It will contain text describing
// the problem.
class FileUploadException extends Exception {}

class NoFileUploadedException extends Exception {}

// This is called to process the posted information from the upload form
// It returns a file_info with 'name' (the original file name)
// and 'tmp_name' (the path to the file).
// It checks for an empty file and for virus infection.
// throws NoFileUploadedException if there is no file
// throws a FileUploadException if there is a problem
// if an exception is thrown any file will have been deleted.
function validate_uploaded_file()
{
    $file_path = "";
    $mode = get_enumerated_param($_REQUEST, 'mode', 'upload', ['upload', 'resumable']);
    try
    {
        if('upload' == $mode)
        {
            $file_info = $_FILES['uploaded_file'];

            // If a user hits the "Upload" button without first selecting a file,
            // it appears that most browsers send a request containing a file whose
            // name and content are empty. But I think it's also legal for a browser
            // to send a request that doesn't contain a file at all (in which case
            // $file_info would be null.  Check both possibilities.
            if (is_null($file_info) || $file_info['name'] == '')
            {
                throw new NoFileUploadedException(_("You must upload a file"));
            }
            $original_name = $file_info['name'];
            $file_path = $file_info['tmp_name'];
            $error = $file_info['error'];
            $file_size = $file_info['size'];

            if ($error != UPLOAD_ERR_OK)
            {
                throw new FileUploadException(get_upload_err_msg($error));
            }
            // there must be only one file.
            $file_count = count($original_name);
            if ($file_count > 1)
            {
                throw new FileUploadException( _("You may only upload one file") );
            }
            // allow anti_virus to read it
            chmod($file_path, 0644);
        }
        else
        {
            // resumable upload
            $root_staging_dir = "/tmp/resumable_uploads";
            $original_name = array_get($_POST, "resumable_filename", "");
            $identifier = array_get($_POST, "resumable_identifier", "");
            $hashed_filename = md5($identifier);
            $file_path = "$root_staging_dir/$hashed_filename";
            $file_size = filesize($file_path);
            $file_info = ["name" => $original_name, "tmp_name" => $file_path];
        }
        // do some checks.
        empty_check($file_size);
        virus_check($file_path);
    }
    catch(FileUploadException $e)
    {
        // delete file here while we know its name
        if(is_file($file_path))
        {
            unlink($file_path);
        }
        throw($e);
    }
    return $file_info;
}

function empty_check($file_size)
{
    if (0 == $file_size) {
        throw new FileUploadException(_("File is empty"));
    }
}

function zip_check($original_name, $file_path)
{
    if (substr($original_name, -4) != ".zip")
    {
        throw new FileUploadException( _("File name extension must be '.zip'") );
    }
    // ensure that it's a valid zip. The extension was already checked and the
    // file is not properly named (it has some temporary name), so we should
    // disable the extension check.
    if (!is_valid_zip_file($file_path, true))
    {
        throw new FileUploadException(sprintf(_("%s is not a valid zip file"),  $original_name));
    }
}

function virus_check($file_path)
{
    global $antivirus_executable;
    if(!$antivirus_executable)
    {
        return;
    }
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
        throw new FileUploadException(sprintf(_("AntiVirus: The scan reported an infection: %s. The upload has been discarded.
            You should perform a complete virus scan on your computer as soon as possible."), $av_test_result[0]));
    }
    else if($av_retval > 1)
    {
        throw new FileUploadException(_("AntiVirus error:") . " " . $av_retval . "<br>" . $av_test_result[0]);
    }
}

function is_valid_filename($filename, $restrict_extension=False)
{
    // Base the filename restrictions on removing anything that could
    // conceivably be a shell escape, control character etc.
    // See http://www.owasp.org/index.php/Unrestricted_File_Upload

    if ($restrict_extension === False) {
        // Ordinarly we allow filenames to start with an alphanumeric, followed
        // by 0 or more alphanumerics, hypens, dashes or periods.
        $regexp = '/^[a-zA-Z_0-9][a-zA-Z_0-9.-]{0,200}$/';
    } else {
        // If we want to restrict filename extensions, '.'s aren't allowed in the
        // body of the filename, and the filename must end with '.ext'
        $regexp = '/^[a-zA-Z_0-9][a-zA-Z_0-9-]{0,200}\.' . $restrict_extension . '$/i';
    }

    // The filename is valid if the regexp matches exactly once.
    return preg_match($regexp, $filename) == 1;
}

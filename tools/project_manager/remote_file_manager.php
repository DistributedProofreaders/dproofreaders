<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'misc.inc'); // get_upload_err_msg(), attr_safe(), html_safe(), startswith()

// Detect if the file uploaded was larger than post_max_size and show
// an error instead of failing silently. We do this here because if the
// POST failed, $_REQUEST and $_POST are empty and we have no data to even
// route them through the do_upload() function at all.
// http://andrewcurioso.com/blog/archive/2010/detecting-file-size-overflow-in-php.html
if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) &&
    empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0)
{
    $max_upload_size = humanize_bytes(return_bytes(ini_get("upload_max_filesize")));
    fatal_error( sprintf(_("Uploaded file is too large. Maximum file size is %s."), $max_upload_size));
}

# Directory structure under uploads dir
$trash_rel_dir   = $uploads_subdir_trash;
$trash_dir       = "$uploads_dir/$trash_rel_dir";

$commons_rel_dir = $uploads_subdir_commons;
$commons_dir     = "$uploads_dir/$commons_rel_dir";

$users_rel_dir   = $uploads_subdir_users;
$users_dir       = "$uploads_dir/$users_rel_dir";

require_login();

// The model we want users to have while interacting with this script is that,
// at any given time, they are "in" a particular directory (folder) within
// $uploads_dir.
// What they can see and do (at that time) are determined by that directory,
// and the commands they submit are interpreted with respect to that directory.
// For the purpose of this script, we call this the "current directory".
// (Note that this has nothing to do with PHP's "current working directory",
// as returned by getcwd(), which is normally just the directory that this
// script lives in.)
//
// Invocations of this script identify the current directory by specifying
// its path relative to $uploads_dir as the value of the script-parameter
// named 'cdrp' (Current Directory Relative Path). They can also leave this
// parameter unset, in which case it defaults to the "home" directory for
// the user. This next section deals with the home directory.

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Home Directory
// --------------

global $pguser;

// TODO: This str_replace() is probably incomplete.
// What about other 'special' characters?
$despecialed_username = str_replace( ' ', '_', $pguser );

// Check user's access to the uploads area.
// The user's remote_file_manager access setting takes priority:
// * 'common' means the user gets access to the Commons directory,
// * 'self' means they get their own home directory
// * 'disabled' means they have no upload access.
// Otherwise, the default policy is that PMs, PFs & SAs
// get their own home dir, and other users have no upload access.
$access_mode = get_access_mode($pguser);
if ($access_mode == 'common' ) {
    $home_dirname = $commons_rel_dir;
    $autoprefix_message = "<b>"._("Uploaded files will automatically be prefixed with your username and an underscore.")."</b>";
} else if ($access_mode == 'self') {
    $home_dirname = "$users_rel_dir/$despecialed_username";
} else if ($access_mode == 'disabled') {
    $home_dirname = NULL;
} else if (user_is_PM() || user_is_proj_facilitator() || user_is_a_sitemanager()) {
    $home_dirname = "$users_rel_dir/$despecialed_username";
} else {
    $home_dirname = NULL;
}

if (is_null($home_dirname)) {
    $page_title = _("Manage your uploads folder");
    output_header($page_title, NO_STATSBAR);
    echo "<h1>$page_title</h1>\n";
    echo "<p>" . _("Your user permissions do not allow access to this script.") . "</p>";
    echo "<p>" . sprintf(_("If you are a Content Provider, please email %s with the subject 'project upload access request' and request access to the 'common' project uploads area in the body of your message."), "<a href='mailto:$db_requests_email_addr'>$db_requests_email_addr</a>") . "</p>";
    echo "<p>" . sprintf(_("If you are a Missing Pages Provider, please email %s with the subject 'project uploads access request' and request 'self' access in the body of your message."), "<a href='mailto:$db_requests_email_addr'>$db_requests_email_addr</a>") . "</p>";
    exit;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

$home_path = "$uploads_dir/$home_dirname";

// Create the user's home directory if it doesn't yet exist.
if (!is_dir($home_path)) {
    // attempt to create $home_path recursively, so that if
    // $users_rel_dir doesn't exist, we create that too
    if (!mkdir($home_path, 0777, TRUE)) {
       show_message('error', _("Could not create home folder!"));
       exit();
    }
    if ($testing)
    {
        chmod($home_path, 0777);
        // so that it's easier to clean up test cases.

        // (mkdir's mode arg is 0777 by default,
        // so this chmod would seem to be a no-op,
        // but mkdir's mode arg is affected by umask,
        // whereas chmod's isn't.)
    }
    $home_dir_created = TRUE;
} else {
    $home_dir_created = FALSE;
}

// Ensure that the Commons folder exists
if (!is_dir($commons_dir)) {
    if(!mkdir($commons_dir)) {
        show_message('error', sprintf( _("Could not create %s folder. Please contact the site administrator."), $commons_rel_dir));
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Current Directory
// -----------------

$curr_relpath = get_current_dir_relative_path($home_dirname);

$curr_abspath = "$uploads_dir/$curr_relpath";
// The absolute path of the current directory.

// $uploads_account is not required and may not be defined if they have not
// set up FTP uploads. If it's blank, lets use a different string instead.
// The point is to provide a "nice" representation of the absolute path
// that doesn't reveal details of the upper reaches of the filesystem.
if($uploads_account) {
    $curr_displaypath = "~$uploads_account/$curr_relpath";
} else {
    $curr_displaypath = "~uploads/$curr_relpath";
}

// For convenience, here are a couple of encoded forms:
$hae_curr_relpath = attr_safe($curr_relpath);
$hce_curr_displaypath = html_safe($curr_displaypath);

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX


// Decide what to do based on the action parameter

if(isset($_REQUEST["resumableIdentifier"]))
    $_REQUEST["action"] = "resumable_chunk";

$action = @$_REQUEST['action'];
if (is_null($action)) {
    // Two possibilities:

    // <input type='image' name='action_is_$action'> kludge in get_actions_block
    // Browser sends {name}.x={pixel} and {name}.y={pixel}.
    // PHP translates the dots into underscores.
    foreach ($_REQUEST as $n => $v) {
        if (preg_match('/^action_is_([a-z]+)_[xy]$/', $n, $matches)) {
            $action = $matches[1];
            break;
        }
    }

    // action defaults to 'showdir'
    if (is_null($action)) {
        $action = 'showdir';
    }
}

$action_message = "";
switch ($action) {
    // We always do showdir after the switch statement
    case 'showdir':    break;
    // Actions that prompt for additional information and we shouldn't showdir
    case 'showupload': do_showupload(); exit;
    case 'showmkdir':  do_showmkdir();  exit;
    case 'showrename': do_showrename(); exit;
    case 'showmove':   do_showmove();   exit;
    case 'showdelete': do_showdelete(); exit;
    // Actions that do an action and do not return an info message
    case 'download':   do_download();   exit;
    case 'upload':     do_upload();     exit;
    case 'resumable_final':
                       do_resumable_final(); exit;
    case 'resumable_chunk':
                       do_resumable_chunk(); exit;
    // Actions that do an action and upon success return an info message
    case 'mkdir':      $action_message = do_mkdir();   break;
    case 'rename':     $action_message = do_rename();  break;
    case 'move':       $action_message = do_move();    break;
    case 'delete':     $action_message = do_delete();  break;
    default:
        // no matching $action in input
        fatal_error(sprintf(_("Invalid action: '%s'"), html_safe($action)));
        exit;
}

do_showdir($action_message);

//---------------------------------------------------------------------------

function do_showdir($action_message)
{
    global $curr_relpath, $hce_curr_displaypath, $home_dirname, $commons_rel_dir;
    global $pguser, $home_dir_created, $autoprefix_message;

    $page_title =  sprintf( _("Manage folder %s"), $hce_curr_displaypath );
    output_header($page_title, NO_STATSBAR);
    echo "<h1>$page_title</h1>\n";

    // If we created a directory for the user, assume this is their first visit
    // and display an informational message - down here after the regular headers
    // are sent, which is why a flag for this exists above.

    if($action_message) {
        show_message('info', $action_message);
    }

    if ( $home_dir_created ) {
        show_message('info', sprintf(_("Home folder created for user %s."), html_safe($pguser)));
    }

    echo "<p>" . _("This page allows you to manage content in this uploads folder.") . "</p>\n";

    if (get_access_mode($pguser) == 'common') {
        show_message('info', _("Your files are located in a common, shared, area.<br><u>Please take care to avoid affecting other users' files.</u>"));
        show_message('info', $autoprefix_message);
    }

    show_form(
        'showupload',
        $curr_relpath,
        _("Click the button to upload a file to this folder") . ":",
        _("Upload a File")
    );

    show_form(
        'showmkdir',
        $curr_relpath,
        _("Click the button to create a new subfolder") . ":",
        _("Create a Subfolder")
    );

    // Display the directory listing
    show_content();

    // if not in their home directory, add a link to jump them there
    if($curr_relpath != $home_dirname)
        show_home_link();

    // if not in the common directory, add a link to jump them there
    if($curr_relpath != $commons_rel_dir)
        show_commons_link();

    // Display Caveats about use on this "main" page only
    show_caveats();
}

function do_showupload()
{
    global $curr_relpath, $hce_curr_displaypath;
    global $pguser, $autoprefix_message;

    // the first part of this blurb is used in upload_text.php
    $standard_blurb = _("<b>Note:</b> Please make sure the file you upload is Zipped (not Gzip, TAR, etc.). The file should have the .zip extension, NOT .Zip, .ZIP, etc.");
    $standard_blurb .= "<br>" . _("The rest of the file's name must consist of ASCII letters, digits, underscores, and/or hyphens. It must not begin with a hyphen.");

    $submit_blurb = "<p>" . sprintf(_("After you click the '%s' button, the browser will appear to be slow getting to the next page. This is because it is uploading the file."), _("Upload")) . "</p>";
    $max_upload_size = humanize_bytes(return_bytes(ini_get("upload_max_filesize")));
    $submit_blurb .= "<p class='warning'>" . sprintf(_('Maximum file size is %s.'), $max_upload_size) . "</p>\n";

    $page_title =  sprintf( _("Upload a file to folder %s"), $hce_curr_displaypath );
    $extra_args = array(
        'js_files' => array("../../pinc/3rdparty/resumablejs/resumable.js"),
        'js_data' => get_resumablejs_loader($curr_relpath),
    );
    output_header($page_title, NO_STATSBAR, $extra_args);
    echo "<h1>$page_title</h1>\n";

    $form_content = "";
    if (get_access_mode($pguser) == 'common') {
        $form_content .= get_message('info', $autoprefix_message);
    }
    $form_content .= "<p style='margin-top: 0em;'>$standard_blurb</p>\n";

    $form_content .= "<div id='old_uploader'>";
    $form_content .= $submit_blurb;
    $form_content .= _("File to upload") . ":&nbsp;";
    $form_content .= "<input type='file' name='the_file' size='50' maxsize='50'>";
    $form_content .= "</div>";

    $form_content .= "<div id='resumable_uploader' style='display: none;'>";
    $form_content .= "<input type='hidden' name='resumable_filename' value=''>";
    $form_content .= "<p>" . _("Your browser supports uploading large files (up to 1GB) via javascript. If the download fails, or you navigate away from this page before it finishes, uploading the file again will pick up where it left off.") . "</p>";
    $form_content .= "<p>" . _("File to upload") . ": <span id='resumable_selected_file'></span> &nbsp; ";
    $form_content .= "<span id='resumable_browse' class='button'>" . _("Choose File") . "</span>";
    $form_content .= "</p>";
    $form_content .= "<p><span id='resumable_submit' class='button'>" . _("Upload") . "</span></p>";
    $form_content .= "<p>" . _("Progress") . ": <span id='resumable_progress'></span></p>";
    $form_content .= "</div>";

    show_form(
        'upload',
        $curr_relpath,
        $form_content,
        _("Upload")
    );

    show_return_link();
}

function do_upload()
{
    return handle_file_upload(@$_FILES['the_file']);
}

function do_resumable_final()
{
    // staging directory for resumable uploads
    $root_staging_dir = "/tmp/resumable_uploads";
    $filename = array_get($_POST, "resumable_filename", "");
    $hashed_filename = md5($filename);
    $uploaded_path = "$root_staging_dir/$hashed_filename/$hashed_filename";

    $file_info = array(
        "error" => UPLOAD_ERR_OK,
        "tmp_name" => $uploaded_path,
        "size" => filesize($uploaded_path),
        "name" => $filename,
        "source" => "resumable",
    );
    return handle_file_upload($file_info);
}

function handle_file_upload($file_info)
{
    global $curr_abspath, $hce_curr_displaypath, $antivirus_executable;
    global $pguser, $despecialed_username;
    global $commons_dir;

    set_time_limit(14400);

    // Disable gzip compression so we can flush the buffer after each step
    // in the process to give the user some progress details. Not that this
    // doesn't necessarily work for all browsers.
    apache_setenv('no-gzip', '1');

    // If a user hits the "Upload" button without first selecting a file,
    // it appears that most browsers send a request containing a file whose
    // name and content are empty. But I think it's also legal for a browser
    // to send a request that doesn't contain a file at all (in which case
    // $file_info would be null.  Check both possibilities.
    if (is_null($file_info) || $file_info['name'] == '') {
        fatal_error( _("You must select a file to upload.") );
    }

    // $file_info has 'name' 'type' 'size' 'tmp_name' 'error'

    if ($file_info['error'] != UPLOAD_ERR_OK) {
        fatal_error( get_upload_err_msg($file_info['error']) );
    }

    show_message('info', _("File uploaded successfully."));

    if ($file_info['size'] == 0) {
        fatal_error( _("File is empty.") );
    }

    if (!is_valid_filename($file_info['name'], "zip")) {
        // create a valid filename re-using the extension provided
        $old_filename = $file_info['name'];
        $path_parts = pathinfo($old_filename);
        $file_info['name'] = preg_replace('/[^a-zA-Z0-9_-]/', '_', $path_parts['filename']) . "." . $path_parts["extension"];
        echo "<p class='warning'>" . sprintf(_('File was renamed to "%1$s" because "%2$s" is not a valid filename'), $file_info['name'], $old_filename) . "</p>";
    }

    // Okay so far, now let's run some tests on the content of the file.

    echo "<p>"._("Examining the uploaded file...")."</p>\n";
    flush();

    $temporary_path = $file_info['tmp_name'];
    // Assuming that TMPDIR or upload_tmp_dir is set sensibly,
    // we don't have to worry about weird characters in $temporary_path.

    // Verify that what was uploaded is actually a zip archive
    // ensure that it's a valid zip

    // The extension was already checked and the file is not properly named (it has some temporary name), so we should
    // disable the extension check.
    if(is_valid_zip_file($temporary_path, true)) {
        show_message('info', _("OK: Valid zip file."));
    } else {
        fatal_error( _("File is not a valid zip file: removing it.") );
    }

    // if an antivirus scanner is installed and configured, scan the file
    if($antivirus_executable) {
        echo "<p>"._("Running a virus scan on the file, please wait...")."</p>\n";
        flush();

        // perform '$antivirus_executable -- <FILENAME>' and expect return
        // value = 0. we use -- to not parse any further arguments starting
        // with -/-- as options
        $av_test_result = array();
        $av_retval=0;

        $cmd = "$antivirus_executable -- " . escapeshellcmd($temporary_path);
        exec($cmd, $av_test_result, $av_retval);
        if ($av_retval == 0) {
            show_message('info', _("OK: AV pass."));
        } else if ($av_retval == 1) {
            show_message('error', _("AV FAIL: The scan reported an infection. The upload has been discarded."));
            show_message('error', $av_test_result[0]);
            show_message('info', _("You should perform a complete virus scan on your computer as soon as possible."));

            // Log the infected upload so that we can track user/frequency
            $reporting_string = "DPSCANS: Infected upload: " . $av_test_result[0];
            error_log($reporting_string);

            show_return_link();
            exit();
        } else {
            fatal_error( _("Undefined AV error message for return value: ").$av_retval );
        }
    }

    // The file passes all tests!

    // Files uploaded to the commons folder should be prefixed with the user's
    // name. This helps identify where the file comes from. We don't prevent
    // the file from being renamed later to remove it, however.
    if(startswith($curr_abspath, $commons_dir) || get_access_mode($pguser) === 'common') {
        $file_prefix = $despecialed_username . "_";
    } else {
        $file_prefix = "";
    }

    $target_name = $file_prefix . $file_info['name'];
    $target_path = "$curr_abspath/$target_name";

    // XXX
    // If there's already something at $temporary_path,
    // this will silently overwrite it.
    // That might or might not be the user's intent.
    if(array_get($file_info, "source", "upload") == "resumable")
    {
        $move_result = rename($temporary_path, $target_path);
        rmdir(dirname($temporary_path));
    }
    else
    {
        $move_result = @move_uploaded_file($temporary_path, $target_path);
    }

    if(!$move_result) {
        fatal_error( _("Webserver failed to copy uploaded file from temporary location to upload folder.") );
    }

    echo "<p>" . sprintf(_('File %1$s successfully uploaded to folder %2$s.'), html_safe($target_name), $hce_curr_displaypath), "</p>\n";

    // Log the file upload
    // In part so that we can possibly clean up with some automation later
    $reporting_string = "DPSCANS: File uploaded to " . $target_path;
    error_log($reporting_string);

    show_return_link();
}

function do_resumable_chunk()
{
    // This function is only called for asynchronous uploads via JS -- nothing
    // printed here will ever be exposed to the user.

    // staging directory for resumable uploads
    $root_staging_dir = "/tmp/resumable_uploads";

    $identifier = array_get($_REQUEST, "resumableIdentifier", "");
    $filename = array_get($_REQUEST, "resumableFilename", "");
    $hashed_filename = md5($filename);
    $chunk_number = array_get($_REQUEST, "resumableChunkNumber", "");
    $total_chunks = array_get($_REQUEST, "resumableTotalChunks", 0);
    $chunk_size = array_get($_REQUEST, "resumableChunkSize", 0);
    $total_size = array_get($_REQUEST, "resumableTotalSize", 0);

    $staging_dir = "$root_staging_dir/$hashed_filename";
    $chunk_filename = "$staging_dir/$hashed_filename.part.$chunk_number";

    // handle testChunks request, this allows uploads to be restarted by
    // allowing the browser to query for which parts have been uploaded
    // successfully
    if($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        if(file_exists($chunk_filename))
            header("HTTP/1.0 200 Ok");
        else
            header("HTTP/1.0 404 Not Found");
    }

    // handle a file upload request
    foreach($_FILES as $file)
    {
        if($file["error"] != UPLOAD_ERR_OK)
        {
            error_log(get_upload_err_msg($file['error']));
            exit;
        }

        if(!is_dir($staging_dir))
        {
            mkdir($staging_dir, 0777, true);
        }

        if(!move_uploaded_file($file['tmp_name'], $chunk_filename))
        {
            error_log("Error saving chunk $chunk_filename for $filename");
            exit;
        }
    }

    if(!is_dir($staging_dir))
        return;

    // attempt to assemble any completed files; this needs to run both for
    // testChunks and uploads to handle edge failure cases where all of the
    // parts have been uploaded but not reassembled

    // Ensure we have every chunk we're looking for and the entire file's
    // worth of data. Chunks are uploaded sequentially, so start at the top
    // and work our way down to fail early.
    $size_on_server = 0;
    for($i=$total_chunks; $i>=1; $i--)
    {
        $chunk_name = "$staging_dir/$hashed_filename.part.$i";

        // if the chunk doesn't exist, return
        if(!is_file($chunk_name))
            return;

        $size_on_server = $size_on_server + filesize($chunk_name);
    }

    // We have all the chunks, but we need to confirm all of them have finished
    // uploading. This can happen if they are being uploaded concurrently.
    if($size_on_server >= $total_size)
    {
        // To prevent multiple instances from trying to do the reassembly
        // concurrently, use a lock file. This should be rare, but we have seen
        // what looks like this behavior and it's easy to work around.
        $lock_filename = "$staging_dir/$hashed_filename.lock";
        if(is_file($lock_filename))
            return;
        else
            touch($lock_filename);

        if(($fp = fopen("$staging_dir/$hashed_filename", "w")) !== FALSE)
        {
            for($i=1; $i<=$total_chunks; $i++)
            {
                $chunk_name = "$staging_dir/$hashed_filename.part.$i";
                fwrite($fp, file_get_contents($chunk_name));
                unlink($chunk_name);
            }
            fclose($fp);
        }
        else
        {
            error_log("Unable to create $staging_dir/$hashed_filename");
        }

        unlink($lock_filename);
    }
}

function do_showmkdir()
{
    global $curr_relpath, $hce_curr_displaypath;

    $page_title =  sprintf( _("Create a subfolder in folder %s"), $hce_curr_displaypath );
    output_header($page_title, NO_STATSBAR);
    echo "<h1>$page_title</h1>\n";

    $form_content = _("Name of subfolder to create") .":&nbsp;<input type='text' name='new_dir_name' size='50' maxsize='50' required>";
    show_form(
        'mkdir',
        $curr_relpath,
        $form_content,
        _("Create")
    );

    show_return_link();
}

function do_mkdir()
{
    global $curr_abspath, $curr_relpath;

    $new_dir_name = @$_POST['new_dir_name'];

    if (!is_valid_filename($new_dir_name)) {
        fatal_error( sprintf(_("Invalid folder name: %s."), $new_dir_name ));
    }

    // XXX For 'common' users, are new subfolders auto-prefixed with their username?

    $new_dir_abspath = "$curr_abspath/$new_dir_name";
    if ( file_exists($new_dir_abspath) ) {
        fatal_error( sprintf(_("%s already exists"), html_safe($new_dir_name)) );
        // hce isn't needed when is_valid_filename()is so bland,
        // but the pattern could change.
    }

    if (!mkdir($new_dir_abspath)) {
        fatal_error( sprintf(_("Unable to create folder")) );
    }

    return sprintf(_("Created folder %s"), html_safe($new_dir_name));
}

function do_showrename()
{
    global $curr_relpath;

    $page_title =  _("Rename an item");
    output_header($page_title, NO_STATSBAR);
    echo "<h1>$page_title</h1>\n";

    $item_name = @$_POST['item_name'];
    confirm_is_local('FD', $item_name, TRUE);

    $form_content = "<input type='hidden' name='item_name' value='" . attr_safe($item_name) . "'>\n";
    $form_content .= sprintf(
        _('Rename <b>%1$s</b> as %2$s'),
        html_safe($item_name),
        "<input type='text' name='new_item_name' size='50' value='" . attr_safe($item_name) . "' required>"
    );

    show_form(
        'rename',
        $curr_relpath,
        $form_content,
        _("Rename")
    );

    show_return_link();
}

function do_rename()
{
    global $curr_abspath;

    $item_name = @$_POST['item_name'];
    confirm_is_local('FD', $item_name);

    $item_path = "$curr_abspath/$item_name";

    $new_item_name = @$_POST['new_item_name'];

    if (!is_valid_filename($new_item_name)) {
        fatal_error( sprintf(_("Invalid new item name: %s."), $new_item_name) );
    }

    if ($new_item_name == $item_name) {
        fatal_error( _("Attempt to rename an item as itself.") );
    }

    if (is_file($item_path)) {
        // Don't let them change the extension (much).
        $item_ext = pathinfo($item_name, PATHINFO_EXTENSION);
        $new_item_ext = pathinfo($new_item_name, PATHINFO_EXTENSION);
        if ( strcasecmp($item_ext, $new_item_ext) != 0 ) {
            fatal_error( _("Attempt to change the filename extension.") );
        }
    }

    $new_item_path = "$curr_abspath/$new_item_name";

    if (file_exists($new_item_path)) {
        fatal_error(sprintf(_("%s already exists"), html_safe($new_item_name)));
    }

    if (!@rename($item_path, $new_item_path)) {
        fatal_error( sprintf(_('Unable to rename item %1$s as %2$s.'), html_safe($item_name), html_safe($new_item_name)) );
    }

    return sprintf(_('Item %1$s has been renamed as %2$s.'), html_safe($item_name), html_safe($new_item_name));
}

function do_showmove()
{
    global $uploads_dir, $commons_dir, $users_dir, $curr_abspath, $curr_relpath, $home_path;

    // NOTE: 'move' is a special case of 'rename' and could be coded as such
    // However, since we only want to allow the user to move a file to a valid
    // user directory, we should probably generate a <SELECT><OPTION>... control
    // from the names of directories in /home/dpscans/

    $page_title =  _("Move a file to another folder");
    output_header($page_title, NO_STATSBAR);
    echo "<h1>$page_title</h1>\n";

    // Get an array of all directory names in the Users directory
    // This is used to identify the "target user"
    // (Which really means that user's directory).
    $valid_target_dirs = searchdir($users_dir, 1, "DIRS");
    // Remove first element (which is $users_dir itself)
    unset($valid_target_dirs[0]);

    // Get all subdirectories in the user's home directory
    $user_subdirs = searchdir($home_path, 2, "DIRS");
    // Remove first element (which is $home_path itself)
    unset($user_subdirs[0]);
    $valid_target_dirs = array_merge($valid_target_dirs, $user_subdirs);

    // Add all subdirectories in the Commons directory
    $common_subdirs = searchdir($commons_dir, 2, "DIRS");
    $valid_target_dirs = array_merge($valid_target_dirs, $common_subdirs);

    // Unique and sort the array
    natcasesort($valid_target_dirs);
    $valid_target_dirs = array_unique($valid_target_dirs);

    $item_name = @$_POST['item_name'];
    confirm_is_local_file($item_name);

    $form_content  = _("<b>Warning:</b> Moving a file to another user cannot be undone.")."</p>";
    $form_content .= "<p>"._("Select the folder that should receive this file").":&nbsp;";
    $form_content .= "<select name='target_dir'>\n";

    foreach($valid_target_dirs as $full_dir) {
        // Don't display the current directory, it's not a valid target
        if ($full_dir == "$curr_abspath/") continue;
        $dir = rtrim(substr($full_dir, strlen("$uploads_dir/")), '/');

        $form_content .= "<option value='" . attr_safe($dir) . "'>" . html_safe($dir) . "</option>\n";
    }
    $form_content .= "</select>\n";
    $form_content .= "<p><b>" . sprintf(
        _("Are you sure you want to move %s?"),
        "<input type='text' name='item_name' size='50' maxsize='50' value='" . attr_safe($item_name) . "' READONLY>"
    ) . "</b>";

    show_form(
        'move',
        $curr_relpath,
        $form_content,
        _("Move File")
    );


    show_return_link();
}

function do_move()
{
    global $uploads_dir, $curr_abspath, $curr_relpath;

    $item_name = @$_POST['item_name'];
    confirm_is_local_file($item_name);

    $src_path = "$curr_abspath/$item_name";

    $dst_dir_relpath = canonicalize_path(trim(@$_POST['target_dir'], '/'));

    if ($dst_dir_relpath === False || !is_valid_move_destination($dst_dir_relpath)) {
        fatal_error( _("Invalid target folder") );
    }

    if ( $dst_dir_relpath == $curr_relpath ) {
        fatal_error( _("The source and destination folders are the same.") );
    }

    $dst_dir = "$uploads_dir/$dst_dir_relpath";
    if (!is_dir($dst_dir)) {
        fatal_error( sprintf(_("%s does not exist, or is not a folder"), html_safe($dst_dir_relpath)) );
    }

    $dst_path = "$dst_dir/$item_name";

    // Test for collision in destination
    if (file_exists($dst_path)) {
        fatal_error( _("File already exists in destination folder.") );
    }

    if (!@rename($src_path, $dst_path)) {
        fatal_error( sprintf(_('Unable to move file %1$s to destination folder: %2$s.'), html_safe($item_name), html_safe($dst_dir_relpath)) );
    }

    return sprintf(_('File %1$s has been moved to folder %2$s'), html_safe($item_name), html_safe($dst_dir_relpath));
}

function do_download()
{
    global $curr_abspath;

    $item_name = @$_POST['item_name'];
    confirm_is_local_file($item_name);

    $src_path = "$curr_abspath/$item_name";

    // The validated filenames we're using don't use any characters requiring escaping,
    // and the escaping of Content-Disposition filenames is complex mess due to late
    // standardization and differing browser implementations. For US-ASCII though,
    // quoted & backslash-escaped names is enough. See the following URLs for more:
    // http://stackoverflow.com/questions/93551/how-to-encode-the-filename-parameter
    // http://greenbytes.de/tech/tc2231/
    $slashed_item_name = addslashes($item_name);
    header("Content-type: " . mime_content_type($src_path) );
    header("Content-disposition: attachment; filename=\"$slashed_item_name\"");

    if (@readfile($src_path) === FALSE) {
        // Switch back to HTML in order to show the error message (and return-link).
        header("Content-type: text/html");
        header("Content-disposition: inline");
        fatal_error( _("Unable to send file") );
    }
}

function do_showdelete()
{
    global $curr_abspath, $curr_relpath, $hce_curr_displaypath;

    $page_title = sprintf(_("Delete an item from folder %s"), $hce_curr_displaypath);
    output_header($page_title, NO_STATSBAR);
    echo "<h1>$page_title</h1>\n";

    $item_name = @$_POST['item_name'];
    confirm_is_local('FD', $item_name);

    $item_path = "$curr_abspath/$item_name";

    // Set up the appropriate descriptor string for the requested delete
    if (is_file($item_path)) {
        $question_template = _("Are you sure you want to delete the file %s?");
    } else if (is_dir($item_path)) {
        $question_template = _("Are you sure you want to delete the folder %s?");
    } else {
        // Shouldn't happen
        fatal_error( _("Unable to determine status of delete request.") );
    }

    $form_content  = "<p style='margin-top: 0em;'>";
    $form_content .= _("<b>Warning:</b> Deletion is permanent and cannot be undone.") . " ";
    $form_content .= _("This script does not check that folders are empty.</p>");
    $form_content .= "<p><b>" . sprintf( $question_template,
        "<input type='text' name='del_file' size='50' maxsize='50' value='" . attr_safe($item_name) . "' READONLY>" ) . "</b></p>";

    show_form(
        'delete',
        $curr_relpath,
        $form_content,
        _("Delete")
    );

    show_return_link();
}

function do_delete()
{
    global $curr_abspath, $trash_dir, $trash_rel_dir;

    $item_name = @$_POST['del_file'];
    confirm_is_local('FD', $item_name);

    $src_path = "$curr_abspath/$item_name";

    // If the trash directory doesn't exist, try to create it.
    if(!is_dir($trash_dir))
    {
        if(!mkdir($trash_dir))
        {
            fatal_error( sprintf(_('Unable to create %s folder. Please contact the site administrator.'), html_safe($trash_rel_dir)) );
        }
    }

    // Use time() to avoid destination collisions
    $dst_path = $trash_dir."/".time()."_".$item_name;

    // For safety, we move the item into TRASH and let the
    // existing cron job remove it instead of using unlink()
    if (!@rename($src_path, $dst_path)) {
        fatal_error( sprintf(_('Unable to move %1$s to %2$s folder.'), html_safe($item_name), html_safe($trash_rel_dir)) );
    }

    return sprintf(_('%1$s has been moved to the %2$s folder for deletion.'), html_safe($item_name), html_safe($trash_rel_dir));
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function user_may_access_all_upload_dirs()
{
    return user_is_a_sitemanager();
}

// Is this a subdirectory of the user's home dir?
function is_subdir_of_home($dir)
{
    global $home_dirname;
    // Does the path start with the home dir?
    // If not, it can't be this user's subdirectory
    if (strpos($dir, "$home_dirname/") !== 0) return False;

    // If there's anything left in the path after stripping
    // off the home dir prefix, it's a user subdirectory.
    $rel = substr($dir, strlen("$home_dirname/"));
    return strlen($rel) !== 0;
}

// May the user move files to the specified relative directory?
function is_valid_move_destination($dir)
{
    global $commons_rel_dir, $users_rel_dir, $home_dirname;

    // Users may move files to the commons directory or its subdirectories
    if (startswith($dir, $commons_rel_dir)) return True;

    // Users may move to subdirectories in their own directory
    if (startswith($dir, "$home_dirname/")) return True;

    // Users may not move files anywhere else except the Users dir.
    if (!startswith($dir, "$users_rel_dir/")) return False;
    $rel = substr($dir, strlen("$users_rel_dir/"));

    // Users may only move files to a user's home directory root
    // and not a subdirectory of it.
    return strpos($rel, "/") === False;
}

// Canonicalise input paths by splitting into components,
// removing empty components, rejecting parent traversal,
// and re-joining them. Returns False is path contains
// invalid components.
function canonicalize_path($relpath)
{
    $canonical_path = array();
        foreach(explode('/', $relpath) as $c) {
        if ($c == '..') return False;
        if ($c == '' || $c == '.') continue;
        $canonical_path[] = $c;
    }
    return join('/', $canonical_path);
}

function get_current_dir_relative_path($home_dirname)
// Ascertain the current directory, validate it, and return the relative path
{
    global $uploads_dir, $commons_rel_dir;
    $abs_uploads_dir = realpath($uploads_dir);

    // Default to home dir if the invocation didn't set cdrp.
    $cdrp = array_get($_REQUEST, 'cdrp', $home_dirname);

    // To prevent leaking information about the local filesystem, all error
    // messages about if a file/directory exists or not need to be the same.
    // If we gave one message if the file exists and another if it wasn't
    // in a normalized form, we allow them a programatic way of determining
    // information about what files/directories exist on the system.
    $error_message = sprintf(_("'%s' does not exist, or is not a folder"), html_safe($cdrp));

    $abspath = realpath("$abs_uploads_dir/$cdrp");
    if($abspath === FALSE) {
        // (It's possible a user could get this without URL-tweaking,
        // if they deleted a directory but still had an old directory listing
        // in another browser window.)
        fatal_error( $error_message );
    }

    // Reject any cdrp that isn't in normalized form
    if ($cdrp != "" && $abspath != "$abs_uploads_dir/$cdrp") {
        fatal_error( $error_message );
    }

    // Only SAs are allowed access to other home folders.
    if (!user_may_access_all_upload_dirs()) {
        if (!startswith("$abspath/", "$abs_uploads_dir/$home_dirname/") &&
            !startswith("$abspath/", "$abs_uploads_dir/$commons_rel_dir/")) {
            fatal_error( _("You are restricted to your home folder, the Commons folder, and their descendants.") );
        }
    }

    return preg_replace("#^$abs_uploads_dir/*#", "", $abspath);
}

function get_access_mode($username)
{
    $userSettings =& Settings::get_settings($username);
        return $userSettings->get_value("remote_file_manager");
}

// Function for displaying directory contents
function show_content()
{
    global $curr_relpath, $hae_curr_relpath, $curr_abspath, $hce_curr_displaypath;

    $item_names = get_directory_items_sorted($curr_abspath);

    if ($item_names === FALSE) return; // XXX fatal_error(_("Unable to open folder")) ?

    // Start table and headers
    $caption_text = sprintf(_("Directory listing for <b>%s</b>"), $hce_curr_displaypath);
    $actions_text = _("Actions");
    echo "
    <table class='themed dirlist'>
        <caption>$caption_text</caption>
        <tr>
            <th class='actions'>$actions_text</th>
            <th>Name</th>
            <th>Time</th>
            <th>Size</th>
        </tr>
    ";

    // Always put the "up" entry at the top.
    // Allow parent directory access if we're in a user-owned subdir, or the user has access to
    // all directories, and isn't already in the root upload directory.
    if (is_subdir_of_home($curr_relpath) || ($curr_relpath != '' && user_may_access_all_upload_dirs()))
    {
        $parent_relpath = dirname($curr_relpath);
        // Canonicalise the root dir so it passes the CDRP checks
        if ($parent_relpath == '.' || $parent_relpath == '/') $parent_relpath = '';
        $url = "?cdrp=" . urlencode($parent_relpath);
        $text = _("up one level");
        echo "
            <tr>
                <td></td>
                <td colspan='3'><a href='$url'>$text</a></td>
            </tr>
        ";
    }

    foreach ( $item_names as $item_name )
    {
        $hce_item_name = html_safe($item_name);

        $item_path = "$curr_abspath/$item_name";

        if (is_file($item_path))
        {
            $actions_blurb =
                get_actions_block( $item_name, array('showdelete', 'download', 'showrename', 'showmove') );

            echo "
            <tr>
                <td class='actions'>$actions_blurb</td>
                <td><img src='wfb_images/wfb_file.gif'>&nbsp;$hce_item_name</td>
                <td class='left-align mono'>".date ('d-M-Y H:i:s', filemtime($item_path))."</td>
                <td class='right-align mono'>".humanize_bytes(filesize($item_path))."</td>
            </tr>
            ";
        } elseif (is_dir($item_path)) {
            $actions_blurb =
                get_actions_block( $item_name, array('showdelete', 'showrename') );
            // If we're in the root directory, subdir paths must be 'foo', not '/foo' to be canonical
            if ($curr_relpath == "") {
                $url = "?cdrp=" . urlencode($item_name);
            } else {
                $url = "?cdrp=" . urlencode("$curr_relpath/$item_name");
            }

            echo "
            <tr>
                <td class='actions'>$actions_blurb</td>
                <td><img src='wfb_images/wfb_directory.gif'>&nbsp;<a href='$url'>$hce_item_name&#47;</a></td>
                <td class='left-align mono'>".date ('d-M-Y H:i:s', filemtime($item_path))."</td>
                <td></td>
            </tr>
            ";
        }
    }
    echo "</table>\n";
}

function get_directory_items_sorted($curr_abspath)
{
    $handle = @opendir($curr_abspath);
    if ($handle === FALSE) return FALSE;

    $items_files = array();
    $items_dirs  = array();
    while ( ($item_name = readdir($handle)) !== FALSE ) {
        if ($item_name == "." || $item_name == "..") continue;

	$curr_entry = "$curr_abspath/$item_name";
	if (is_dir($curr_entry)) {
	    $items_dirs[] = $item_name;
	} elseif (is_file($curr_entry)) {
	    $items_files[] = $item_name;
	}
    }
    closedir($handle);
    sort($items_dirs);
    sort($items_files);
    return array_merge( $items_dirs, $items_files );
}

function get_actions_block( $item_name, $valid_actions )
{
    global $hae_curr_relpath;
    $hae_item_name = attr_safe($item_name);

    $form = "
        <form style='display:inline;' action='?' method='POST' enctype='multipart/form-data'>
        <input type='hidden' name='cdrp' value='$hae_curr_relpath'>
        <input type='hidden' name='item_name' value='$hae_item_name'>
    ";

    $actions_info = array(
        'showdelete' =>
            array( _('Delete this item'),   'wfb_images/wfb_delete.png',   '[X]' ),
        'download' =>
            array( _('Download this item'), 'wfb_images/wfb_download.png', '[D]' ),
        'showrename' =>
            array( _('Rename this item'),   'wfb_images/wfb_rename.png',   '[R]' ),
        'showmove' =>
            array( _('Move this item'),     'wfb_images/wfb_move.png',     '[M]' ),
    );

    foreach ( $actions_info as $action => $action_info )
    {
        if (!in_array($action, $valid_actions)) {
            // Insert an empty spot, so that, for example,
            // all the "rename" icons line up vertically.
            $icon = 'wfb_images/wfb_transparent.gif';
            $form .= "
                <image src='$icon'>
            ";
            continue;
        }

        list($blurb, $icon, $alt) = $action_info;
        /*
        I tried:
            $form .= "
                <input type='image' name='action' value='$action'
                    title='$blurb' src='$icon' alt='$alt'>
            ";
        And it worked fine in Firefox 2/3 and Safari
        (and would have in Chrome too, apparently).
        But IE and Opera (and Firefox 4 because of HTML5)
        don't send name/value pair for <input type='image'>,
        so the script doesn't get action=$action,
        so this doesn't work.

        So I tried:
            $form .= "
                <button type='submit' name='action' value='$action' title='$blurb'>
                    <img src='$icon' alt='$alt'>
                </button>
            ";
        and it works, but looks different (worse),
        like a standard button with an image stuck on it.

        So now I'm trying the following, with some compensating code up where
        $_POST['action'] is extracted.
        */

        $form .= "
            <input type='image' name='action_is_$action'
                title='$blurb' src='$icon' alt='$alt'>
        ";
    }

    $form .= "</form>";
    return $form;
}

function confirm_is_local_file($filename)
// If $filename is a valid filename parameter,
// and names a file in the current directory, return.
// Otherwise, print an error message and exit.
{
    confirm_is_local('F', $filename);
}

function confirm_is_local($type, $item_name)
{
    global $curr_abspath, $hce_curr_displaypath;

    assert( $type == 'F' || $type == 'D' || $type == 'FD' );

    // NB this catches $item_name == NULL too
    if ( $item_name == '' ) {
        fatal_error( _("Item name must not be empty.") );
    }

    if ( strpos($item_name, '/') !== FALSE ) {
        fatal_error( _("Item name must not contain a slash character") );
    }

    $src_path = "$curr_abspath/$item_name";

    // Note that 'file_exists', despite the name, doesn't require
    // that its arg identify a file (as opposed to a directory).
    if (!file_exists($src_path)) {
        fatal_error( sprintf(_('folder %1$s does not have an item named %2$s'), $hce_curr_displaypath, html_safe($item_name)) );
    }

    if ($type == 'FD') return;

    if ($type == 'F') {
        $exists = is_file($src_path);
        $msg = _("%s exists, but is not a file");
    } else if ($type == 'D') {
        $exists = is_dir($src_path);
        $msg = _("%s exists, but is not a folder");
    } else {
        assert(FALSE);
    }

    if (!$exists) {
        fatal_error( sprintf($msg, html_safe($item_name)) );
    }
}

function fatal_error($message)
{
    show_message('error', $message);

    if (isset($GLOBALS['curr_relpath'])) {
        show_return_link();
    } else {
        show_home_link();
    }

    exit();
}

// return or echo a formatted informational or error message
function get_message($type, $message)
{
    if ($type == 'error') {
        $prefix = _("Error") . ":";
        $style = "color: red;";
    } else {
        $prefix = _("Info") . ":";
        $style = "background-color: lightgreen; color: black;";
    }
    $style .= " border: 1px solid black; padding: .5em; margin: 0.8em 0;";
    return "<div style='$style'><b>$prefix</b> $message</div>\n";
}

function show_message($type, $message)
{
    echo get_message($type, $message);
    flush();
}

// Display a return link (to the 'showdir' view)
function show_return_link($relpath=NULL)
{
    global $curr_relpath;
    if($relpath === NULL)
        $relpath = $curr_relpath;

    $url = "?cdrp=" . urlencode($relpath);
    $text = sprintf(_("Go to folder %s"), attr_safe($relpath));
    echo "<p><a href='$url'>$text</a></p>\n";
}

function show_home_link()
{
    $text = sprintf(_("Go to your home folder"));
    echo "<p><a href='?action=showdir'>$text</a></p>\n";
}

function show_commons_link()
{
    global $commons_rel_dir;
    $text = sprintf(_("Go to Commons folder"));
    $url = "?cdrp=" . urlencode($commons_rel_dir);
    echo "<p><a href='$url'>$text</a></p>\n";
}

function searchdir( $dir_path, $maxdepth = -1, $mode = "FULL", $d = 0 )
{
    // $dir_path : path to browse
    // $maxdepth : how deep to browse (-1=unlimited)
    // $mode : "FULL"|"DIRS"|"FILES"
    // $d : must not be defined

    if ( substr( $dir_path, strlen($dir_path)-1 ) != '/' ) { $dir_path .= '/'; }
    $selected_entries = array();
    if ( $mode != "FILES" ) { $selected_entries[] = $dir_path; }
    if ( $handle = opendir($dir_path) )
    {
        while ( false !== ( $item_name = readdir($handle) ) )
        {
            if ( $item_name != '.' && $item_name != '..' )
            {
                $item_path = $dir_path . $item_name;
                if ( ! is_dir($item_path) )
                {
                    if ( $mode != "DIRS" ) { $selected_entries[] = $item_path; }
                }
                elseif ( $d >=0 && ($d < $maxdepth || $maxdepth < 0) )
                {
                    $result = searchdir( $item_path . '/', $maxdepth, $mode, $d + 1 );
                    $selected_entries = array_merge($selected_entries, $result);
                }
            }
        }
        closedir($handle);
    }
    if ( $d == 0 ) { natcasesort($selected_entries); }
    return ( $selected_entries );
}


// ===================================================================================
function show_form($action, $cdrp, $form_content, $submit_label)
{
    // Display a div with a form containing action and cdrp hidden inputs; some content,
    // which can be abritrary HTML/other inputs; and finally a labeled submit button
    echo "<div id='$action' style='border: 1px solid grey; margin-left: .5em; padding: .25em;'>\n";
    echo "<form id='${action}_form' style='margin: 0em;' action='?' method='POST' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='action' value='" . attr_safe($action) . "'>\n";
    echo "<input type='hidden' name='cdrp' value='" . attr_safe($cdrp) . "'>\n";
    echo "$form_content&nbsp;<input id='${action}_submit' type='submit' value='$submit_label'>\n";
    echo "</form>\n";
    echo "</div>\n";
}

function show_caveats()
{
    echo "<p><b>" . _("Current file and directory management features") . ":</b></p>\n";
    echo "<ul>\n";
    echo "<li>" . _("Upload files into your user folder.") . "\n";
    echo "<ul>\n";
    echo   "<li>" . _("Files are tested for validity and scanned for viruses.") . "</li>\n";
    echo "</ul>";
    echo "</li>\n";
    echo "<li>" . _("Download files from your user folder.") . "</li>\n";
    echo "<li>" . _("Delete files in your user folder.") . "</li>\n";
    echo "<li>" . _("Delete folders in your user folder.") . "</li>\n";
    echo "<li>" . _("Transfer files to other users.") . "</li>\n";
    echo "<li>" . _("Browse into subfolders.") . "</li>\n";
    echo "<li>" . _("Create subfolders.") . "</li>\n";
    echo "<li>" . _("Rename files and folders.") . "</li>\n";
    echo "</ul>\n";
}


// Move these to misc.inc?

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

function return_bytes($size_str)
// from http://php.net/manual/en/function.ini-get.php
{
    switch (substr($size_str, -1))
    {
        case 'M': case 'm': return (int)$size_str * 1048576;
        case 'K': case 'k': return (int)$size_str * 1024;
        case 'G': case 'g': return (int)$size_str * 1073741824;
        default: return $size_str;
    }
}

function get_resumablejs_loader($cdrp)
{
    global $code_url;

    $upload_failed = javascript_safe(_("Upload failed"));
    $finalizing_upload = javascript_safe(_("Upload complete. Running file checks, please wait..."));

    return <<<EOS
$(document).ready(function() {
    var r = new Resumable({
        target: '$code_url/tools/project_manager/remote_file_manager.php',
        testTarget: '$code_url/tools/project_manager/remote_file_manager.php',
        forceChunkSize: true,
        maxFiles: 1,
        fileType: ['zip'], // use extension not mime type since zips have many
        maxFileSize: 1024*1024*1024  // 1GB
    });

    // Check to see if the browser supports resumable, and if so expose it
    if(r.support) {
        r.assignBrowse($(resumable_browse));
        r.assignDrop($(upload));

        // Show the resumable div
        $(resumable_uploader).show();

        // Hide the old uploader and submit button
        $(old_uploader).hide();
        $(upload_submit).hide();
    }

    // Before we start the upload, prevent the user from selecting another
    // file or hitting upload again.
    $(resumable_submit).click(function() {
        $(resumable_browse).hide();
        $(resumable_submit).hide();
        r.upload();
    });

    // After a file has been selected, display its name
    r.on('fileAdded', function(file, event) {
        $(resumable_selected_file).text(file.fileName);
    });

    // After a file has been successfully uploaded, we update the form
    // and submit it for final validation (AV scan, etc).
    r.on('fileSuccess', function(file, message) {
        $('input[name="resumable_filename"]').val(file.fileName);
        $('input[name="action"]').val("resumable_final");
        $(resumable_progress).text("$finalizing_upload");
        $(upload_form).submit();
    });

    // If an error occured, re-enable the upload form and show a message
    r.on('fileError', function(file, message) {
        $(resumable_browse).show();
        $(resumable_submit).show();
        $(resumable_progress).text("$upload_failed<br>" + message);
    });

    // As the file upload progresses, show a percentage complete
    r.on('progress', function() {
        $(resumable_progress).text( (r.progress() * 100).toFixed(2) + '%');
    });
});
EOS;
}

// vim: sw=4 ts=4 expandtab

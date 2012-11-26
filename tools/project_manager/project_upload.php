<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'misc.inc'); // get_upload_err_msg()

if ( get_magic_quotes_gpc() )
{
    $_GET     = array_map('stripslashes', $_GET);
    $_POST    = array_map('stripslashes', $_POST);
    $_REQUEST = array_map('stripslashes', $_REQUEST);
}

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
if (dpscans_access_mode($pguser) === 'common' ) {
    $home_dirname = "Commons";
    $autoprefix_message = "<b>"._("Uploaded files will automatically be prefixed with your username and an underscore.")."</b>";
} else if (dpscans_access_mode($pguser) === 'self') {
    $home_dirname = $despecialed_username;
} else if (user_is_PM() || user_is_proj_facilitator() || user_is_a_sitemanager()) {
    $home_dirname = $despecialed_username;
} else {
    $page_title = sprintf( _("Manage your %s folder"), $uploads_account );
    theme($page_title, "header");
    echo "<h1>$page_title</h1>\n";
    $blurb1 = _("Your user permissions do not allow access to this script.");
    $blurb2 = sprintf( _("If you are a Content Provider, please email db-req with the subject '%s access request' and request access to the 'common' %s area in the body of your message."), $uploads_account, $uploads_account );
    $blurb3 = sprintf( _("If you are a Missing Pages Provider, please email db-req with the subject '%s access request' and request 'self' access to %s."), $uploads_account, $uploads_account );
    echo "<p><b>$blurb1</b></p>";
    echo "<p>$blurb2</p>";
    echo "<p>$blurb3</p>";
    theme("", "footer");
    exit;
}

$home_path = "$uploads_dir/$home_dirname";

// Create the user's home directory if it doesn't yet exist.
if (!is_dir($home_path)) {
    if (!mkdir($home_path)) {
       showMessage('error', _("Could not create home folder!"));
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

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Current Directory
// -----------------

// Here, we ascertain the current directory, validate it, and set various
// variables.

// The value of script-paramater 'cdrp' (Current Directory Relative Path)
// is carried by the variable $curr_relpath, but we don't set the latter
// until the former validates.

// Default to home dir if the invocation didn't set cdrp.
$cdrp = array_get($_REQUEST, 'cdrp', $home_dirname);

// Validate it.
// (Yeah, do this even if we just set it to $home_dirname, as a sanity check.)
// Note that, normally, users won't see any of these messages.
// They would probably have to be tweaking URLs to see them.
// Thus, they don't have to be that polite or informative.

// Tests:
// $cdrp = '..';
// $cdrp = '../../etc/passwd';
// $cdrp = 'a///b';
// $cdrp = '//c//d//';
// $cdrp = '/././e/f/';
// $cdrp = 'e/f';

// To simplify subsequent patterns, temporarily tack a slash on either end.
$cdrp_temp1 = "/$cdrp/";

// If any component of the path is '..'
// the requester is trying something sneaky.
if (str_contains($cdrp_temp1, '/../')) {
    fatalError( _("Invalid cdrp") );
}

// Do some normalization on the path.
// 1. Collapse consecutive slashes.
// 2. Eliminate components consisting only of '.'.
$cdrp_temp2 = preg_replace(
    array('!/+!', '!/\.(?=/)!'), 
    array('/',     ''),
    $cdrp_temp1
);

// XXX
// Do we reject any cdrp that isn't in normalized form?
if (TRUE) {
    if ($cdrp_temp2 != $cdrp_temp1) {
        fatalError( _("cdrp was not in normalized form") );
    }
}

// XXX
// Do we restrict a user to their home directory and its descendants?
if (TRUE) {
    if (!startswith($cdrp_temp2, "/$home_dirname/")) {
        fatalError( _("You are restricted to your home folder and its descendants") );
    }
}

// At the point, we can get rid of the end-slashes:
$cdrp_temp3 = trim($cdrp_temp2, '/');

// Finally, does the specified directory actually exist?
if (!is_dir("$uploads_dir/$cdrp_temp3")) {
    // (It's possible a user could get this without URL-tweaking,
    // if they deleted a directory but still had an old directory listing
    // in another browser window.)
    fatalError( sprintf(_("'%s' does not exist, or is not a folder"), hce($cdrp_temp3)) );
}

// Yay, cdrp has passed all the tests!

$curr_relpath = $cdrp_temp3;

$curr_abspath = "$uploads_dir/$curr_relpath";
// The absolute path of the current directory.

$curr_displaypath = "~$uploads_account/$curr_relpath";
// A "nice" representation of the absolute path,
// one that doesn't reveal details of the upper reaches of the filesystem.

// For convenience, here are a couple of encoded forms:
$hae_curr_relpath = hae($curr_relpath);
$hce_curr_displaypath = hce($curr_displaypath);

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

$action = @$_REQUEST['action'];
if (is_null($action)) {
    // Two possibilities:

    // <input type='image' name='action_is_$action'> kludge in getActionsBlock
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

// -----------------------------------------------------------------------------
if ($action == 'showdir') {
    $page_title =  sprintf( _("Manage folder %s"), $hce_curr_displaypath );
    theme($page_title, "header");
    echo "<h1>$page_title</h1>\n";

    // If we created a directory for the user, assume this is their first visit
    // and display an informational message - down here after the regular headers
    // are sent, which is why a flag for this exists above.

    if ( $home_dir_created ) {
        showMessage('info', sprintf(_("Home folder created for user %s."), $pguser));
    }

    echo "<p>" . sprintf( _("This page allows you to manage content in this %s folder.<br>Additional file management features are gradually being added. See Current Conditions, below."), $uploads_account ) . "</p>\n";

    if (dpscans_access_mode($pguser) == 'common') {
        showMessage('info', _("Because you are not a PM, your files are located in a common, shared area.<br><u>Please take care to avoid affecting other users' files.</u>"));
        showMessage('info', $autoprefix_message);
    }

    $upload_preamble = _("Click the button to upload a file to this folder:");
    $upload_label = _("Upload a File");
    echo "<div style='border: 1px solid grey; margin-left: .5em; padding: .25em;'>\n";
    echo "<form style='margin: 0em;' action='?' method='POST' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='action' value='showupload'>\n";
    echo "<input type='hidden' name='cdrp' value='$hae_curr_relpath'>\n";
    echo "$upload_preamble&nbsp;<input type='submit' value='$upload_label'>\n";
    echo "</form>\n";
    echo "</div>\n";

    $mkdir_preamble = _("Click the button to create a new subfolder:");
    $mkdir_label = _("Create a Subfolder");
    echo "<div style='border: 1px solid grey; margin-left: .5em; padding: .25em;'>\n";
    echo "<form style='margin: 0em;' action='?' method='POST' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='action' value='showmkdir'>\n";
    echo "<input type='hidden' name='cdrp' value='$hae_curr_relpath'>\n";
    echo "$mkdir_preamble&nbsp;<input type='submit' value='$mkdir_label'>\n";
    echo "</form>\n";
    echo "</div>\n";

    // Display the directory listing
    showContent();

    // Display Caveats about use on this "main" page only
    showCaveats();

    theme("", "footer");

// -----------------------------------------------------------------------------
} else if ($action == 'showupload') {

    $standard_blurb = _("<b>Note:</b> Please make sure the file you upload is Zipped (not Gzip, TAR, etc.).<br> The file should have the .zip extension, NOT .Zip, .ZIP, etc.<br>The rest of the file's name must consist of ASCII letters, digits, underscores, and/or hyphens. It must not begin with a hyphen.");
    $submit_blurb = _("After you click the '%s' button, the browser will appear to be slow getting to the next page. This is because it is uploading the file.");

    $page_title =  sprintf( _("Upload a file to folder %s"), $hce_curr_displaypath );
    theme($page_title, "header");
    echo "<h1>$page_title</h1>\n";

    echo "<div style='border: 1px solid grey; margin-left: .5em; padding: .25em;'>\n";
    echo "<form style='margin: 0em;' action='?' method='POST' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='action' value='upload'>\n";
    echo "<input type='hidden' name='cdrp' value='$hae_curr_relpath'>\n";
    if (dpscans_access_mode($pguser) == 'common') {
        showMessage('info', $autoprefix_message);
    }
    echo "<p style='margin-top: 0em;'>$standard_blurb</p>\n";
    echo _("File to upload:")."&nbsp;";
    echo "<input type='file' name='the_file' size='25' maxsize='50'>&nbsp;";
    echo "<input type='submit' value='" . _("Upload") ."'>\n";
    echo "</form>\n";
    echo "</div>\n";

    // Display the users directory listing
//    showContent();

    showReturnLink();
    theme("", "footer");

// -----------------------------------------------------------------------------
} else if ($action == 'upload') {

    set_time_limit(14400);

    $file_info = @$_FILES['the_file'];

    // If a user hits the "Upload" button without first selecting a file,
    // it appears that most browsers send a request containing a file whose
    // name and content are empty. But I think it's also legal for a browser
    // to send a request that doesn't contain a file at all (in which case
    // $file_info would be null.  Check both possibilities.
    if (is_null($file_info) || $file_info['name'] == '') {
        fatalError( _("You must select a file to upload.") );
    }

    // $file_info has 'name' 'type' 'size' 'tmp_name' 'error'

    if ($file_info['error'] != UPLOAD_ERR_OK) {
        fatalError( get_upload_err_msg($file_info['error']) );
    }

    if ($file_info['size'] == 0) {
        fatalError( _("File is empty.") );
    }

    // Check that the user-supplied name is okay.
    $okay_filename_pattern = '/^[a-zA-Z_0-9][a-zA-Z_0-9-]{0,200}\.zip$/';
    // Somewhat more simply:
    // $okay_filename_pattern = '/^\w[\w-]{0,200}\.zip$/';
    // That would usually mean the same, because \w means
    // any letter or digit or the underscore character.
    // But the meaning of "letter" and "digit" is locale-dependent,
    // so the pattern involving \w might be more permissive.
    // On the other hand, that might be what some site wants.
    //
    if (!preg_match($okay_filename_pattern, $file_info['name'])) {
        fatalError( _("Invalid Filename.") );
        // (Alternatively, we could construct a name that *was* okay,
        // and use that instead.)
    }

    // Okay so far, now let's run some tests on the content of the file.

    echo "<p>"._("Examining the uploaded file")."</p>\n";

    $temporary_path = $file_info['tmp_name'];
    // Assuming that TMPDIR or upload_tmp_dir is set sensibly,
    // we don't have to worry about weird characters in $temporary_path.

    // Verify that what was uploaded is actually a zip archive
    $zip_test_result = array();
    $zip_retval=0;
    exec("/usr/bin/file -b -i $temporary_path", $zip_test_result, $zip_retval);
    if ($zip_test_result[0] == 'application/x-zip') {
        showMessage('info', _("OK: Valid zip file."));
    } else {
        fatalError( _("File is not a valid zip file: removing it.") );
    }
    // XXX /usr/bin/file only looks at the first few bytes of the file.
    // Maybe we should check the whole file's integrity with 'unzip -t'.

    // Anti-virus check: perform 'clamscan <FILENAME>' and expect return value = 0
    $av_test_result = array();
    $av_retval=0;
    if (!$testing) exec("/usr/bin/clamscan $temporary_path", $av_test_result, $av_retval);
    if ($av_retval == 0) {
        showMessage('info', _("OK: AV pass."));
    } else if ($av_retval == 1) {
        showMessage('error', _("AV FAIL: The scan reported an infection. The upload has been discarded."));
        showMessage('error', $av_test_result[0]);
        showMessage('info', _("You should perform a complete virus scan on your computer as soon as possible."));

        // Log the infected upload so that we can track user/frequency
        $reporting_string = "DPSCANS: Infected upload: " . $av_test_result[0];
        error_log($reporting_string);

        showReturnLink();
        exit();
    } else {
        fatalError( _("Undefined AV error message for return value: ").$av_retval );
    }

    // The file passes all tests!

    if (dpscans_access_mode($pguser) === 'common') {
        $file_prefix = $despecialed_username . "_";
    } else {
        $file_prefix = "";
    }
    $installed_name = $file_prefix . $file_info['name'];
    $installed_path = $curr_abspath . "/" . $installed_name;

    // XXX
    // If there's already something at $installed_path,
    // this will silently overwrite it.
    // That might or might not be the user's intent.

    if (! @move_uploaded_file($temporary_path, $installed_path) ) {
        fatalError( _("Failed to install the file.") );
    }

    echo "<p>" . sprintf(_("File %s successfully uploaded to folder %s."), hce($installed_name), $hce_curr_displaypath), "</p>\n";

    // Log the file upload
    // In part so that we can possibly clean up with some automation later
    $reporting_string = "DPSCANS: File uploaded to " . $installed_path;
    error_log($reporting_string);

    showReturnLink();

// -----------------------------------------------------------------------------
} else if ($action == 'showmkdir') {

    $page_title =  sprintf( _("Create a subfolder in folder %s"), $hce_curr_displaypath );
    theme($page_title, "header");
    echo "<h1>$page_title</h1>\n";

    echo "<div style='border: 1px solid grey; margin-left: .5em; padding: .25em;'>\n";
    echo "<form style='margin: 0em;' action='?' method='POST' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='action' value='mkdir'>\n";
    echo "<input type='hidden' name='cdrp' value='$hae_curr_relpath'>\n";
    echo _("Name of subfolder to create:")."&nbsp;";
    echo "<input type='text' name='new_dir_name' size='25' maxsize='50'>&nbsp;";
    echo "<input type='submit' value='" . _("Create") ."'>\n";
    echo "</form>\n";
    echo "</div>\n";

    showReturnLink();
    theme("", "footer");

// -----------------------------------------------------------------------------
} else if ($action == 'mkdir') {

    $new_dir_name = @$_POST['new_dir_name'];

    $okay_dirname_pattern = '/^[a-zA-Z_0-9][a-zA-Z_0-9.-]{0,200}$/';
    if (!preg_match($okay_dirname_pattern, $new_dir_name)) {
        fatalError( _("Invalid folder name.") );
    }

    // XXX For 'common' users, are new subfolders auto-prefixed with their username?

    $new_dir_abspath = "$curr_abspath/$new_dir_name";
    if ( file_exists($new_dir_abspath) ) {
        fatalError( sprintf(_("%s already exists"), hce($new_dir_name)) );
        // hce isn't needed when $okay_dirname_pattern is so bland,
        // but the pattern could change.
    }

    $r = mkdir($new_dir_abspath);
    if (!$r) {
        fatalError( sprintf(_("Unable to create folder")) );
    }

    showMessage('info', sprintf(_("Created folder %s"), hce($new_dir_name)));
    showReturnLink();

// -----------------------------------------------------------------------------
} else if ($action == 'showrename') {

    $page_title =  _("Rename an item");
    theme($page_title, "header");
    echo "<h1>$page_title</h1>\n";

    $item_name = @$_POST['item_name'];
    confirmIsLocal('FD', $item_name, TRUE);

    $hae_item_name = hae($item_name);

    echo "<form style='margin: 0em;' action='?' method='POST' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='action' value='rename'>\n";
    echo "<input type='hidden' name='cdrp' value='$hae_curr_relpath'>\n";
    echo "<input type='hidden' name='item_name' value='$hae_item_name'>\n";
    echo "<p>";
    echo sprintf(
        _("Rename %s as %s"),
        hce($item_name),
        "<input type='text' name='new_item_name' size='25'>"
    );
    echo "<input type='submit' value='". _("Rename") . "'>\n";
    echo "</p>\n";
    echo "</form>\n";

    showReturnLink();
    theme("", "footer");

// -----------------------------------------------------------------------------
} else if ($action == 'rename') {

    $item_name = @$_POST['item_name'];
    confirmIsLocal('FD', $item_name);

    $item_path = "$curr_abspath/$item_name";

    $new_item_name = @$_POST['new_item_name'];

    $okay_item_name_pattern = '/^[a-zA-Z_0-9][a-zA-Z_0-9.-]{0,200}$/';
    if (!preg_match($okay_item_name_pattern, $new_item_name)) {
        fatalError( _("Invalid new item name.") );
    }

    if ($new_item_name == $item_name) {
        fatalError( _("Attempt to rename an item as itself.") );
    }

    if (is_file($item_path)) {
        // Don't let them change the extension (much).
        $item_ext = pathinfo($item_name, PATHINFO_EXTENSION);
        $new_item_ext = pathinfo($new_item_name, PATHINFO_EXTENSION);
        if ( strcasecmp($item_ext, $new_item_ext) != 0 ) {
            fatalError( _("Attempt to change the filename extension.") );
        }
    }

    $new_item_path = "$curr_abspath/$new_item_name";

    if (file_exists($new_item_path)) {
        fatalError( sprintf(_("%s already exists"), $new_item_name) );
    }

    $r = @rename($item_path, $new_item_path);
    if (!$r) {
        fatalError( sprintf(_("Unable to rename item %s as %s."), hce($item_name), hce($new_item_name)) );
    }

    showMessage('info', sprintf(_("Item %s has been renamed as %s."), hce($item_name), hce($new_item_name)));
    showReturnLink();

// -----------------------------------------------------------------------------
} else if ($action == 'showmove') {

    // NOTE: 'move' is a special case of 'rename' and could be coded as such
    // However, since we only want to allow the user to move a file to a valid
    // user directory, we should probably generate a <SELECT><OPTION>... control
    // from the names of directories in /home/dpscans/

    $page_title =  _("Move a file to another user's folder");
    theme($page_title, "header");
    echo "<h1>$page_title</h1>\n";

    // Get an array of all directory names in the uploads directory
    // This is used to identify the "target user"
    // (Which really means that user's directory).
    $valid_target_dirs = searchdir($uploads_dir."/", 1, "DIRS");
    unset($valid_target_dirs[0]); // Remove first element (which is $uploads_dir itself)

    $item_name = @$_POST['item_name'];
    confirmIsLocalFile($item_name);

    echo "<p><b>"._("Warning:")."</b> "._("Moving a file to another user cannot be undone.")."</p>";
    echo "<form style='margin: 0em;' action='?' method='POST' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='action' value='move'>\n";
    echo "<input type='hidden' name='cdrp' value='$hae_curr_relpath'>\n";
    echo "<p>"._("Select the folder of the user who should receive this file:")."&nbsp;";
    echo "<select name='target_dir'>\n";

    foreach($valid_target_dirs as $u) {
        // Don't display the current directory, it's not a valid target
        if ($u == "$curr_abspath/") continue;

        $w = explode("/", trim($u, "/")); // Get rid of leading and trailing slash first
        $v = array_pop($w);

        $hae_v = hae($v);
        $hce_v = hce($v);
        echo "<option value='$hae_v'>$hce_v</option>\n";
    }
    echo "</select>\n";

    $hae_item_name = hae($item_name);
    $question = sprintf(
        _("Are you sure you want to move&nbsp;%s&nbsp;?"),
        "<input type='text' name='item_name' size='25' maxsize='50' value='$hae_item_name' READONLY>"
    );
    echo "<p><b>$question</b>&nbsp;";
    echo "<input type='submit' value='" . _("Move File") ."'>\n";
    echo "</p>";
    echo "</form>\n";

    showReturnLink();
    theme("", "footer");

// -----------------------------------------------------------------------------
} else if ($action == 'move') {

    $item_name = @$_POST['item_name'];
    confirmIsLocalFile($item_name);

    $src_path = "$curr_abspath/$item_name";

    $dst_dir_relpath = @$_POST['target_dir'];

    $dst_dir_relpath = trim($dst_dir_relpath, '/');
    if ( str_contains($dst_dir_relpath, '/')
        || $dst_dir_relpath == '..'
        || $dst_dir_relpath == '.'
        || $dst_dir_relpath == ''
    ) {
        fatalError( _("Invalid target folder") );
    }

    if ( $dst_dir_relpath == $curr_relpath ) {
        fatalError( _("The source and destination folders are the same.") );
    }

    $dst_dir = "$uploads_dir/$dst_dir_relpath";
    if (!is_dir($dst_dir)) {
        fatalError( sprintf(_("%s does not exist, or is not a folder"), hce($dst_dir_relpath)) );
    }

    $dst_path = "$dst_dir/$item_name";

    // Test for collision in destination
    if (file_exists($dst_path)) {
        fatalError( _("File already exists in destination folder.") );
    }

    $result = @rename($src_path, $dst_path);
    if (!$result) {
        fatalError( sprintf(_("Unable to move file %s to destination folder: %s."), hce($item_name), hce($dst_dir_relpath)) );
    }

    showMessage('info', sprintf(_("File %s has been moved to folder %s"), hce($item_name), hce($dst_dir_relpath)));
    showReturnLink();

// -----------------------------------------------------------------------------
} else if ($action == 'download') {

    $item_name = @$_POST['item_name'];
    confirmIsLocalFile($item_name);

    $src_path = "$curr_abspath/$item_name";

    $slashed_item_name = addslashes($item_name);
    header("Content-type: " . mime_content_type($src_path) );
    header("Content-disposition: attachment; filename=\"$slashed_item_name\"");

    $r = @readfile($src_path);
    if ( $r === FALSE ) {
        // Switch back to HTML in order to show the error message (and return-link).
        header("Content-type: text/html");
        header("Content-disposition: inline");
        fatalError( _("Unable to send file") );
    }

// -----------------------------------------------------------------------------
} else if ($action == 'showdelete') {

    $page_title = sprintf(_("Delete an item from folder %s"), $hce_curr_displaypath);
    theme($page_title, "header");
    echo "<h1>$page_title</h1>\n";

    $item_name = @$_POST['item_name'];
    confirmIsLocal('FD', $item_name);

    $item_path = "$curr_abspath/$item_name";

    // Set up the appropriate descriptor string for the requested delete
    if (is_file($item_path)) {
        $question_template = _("Are you sure you want to delete the file&nbsp;%s&nbsp;?");
    } else if (is_dir($item_path)) {
        $question_template = _("Are you sure you want to delete the folder&nbsp;%s&nbsp;?");
    } else {
        // Shouldn't happen
        fatalError( _("Unable to determine status of delete request.") );
    }

    $hae_item_name = hae($item_name);
    $question = sprintf( $question_template,
        "<input type='text' name='del_file' size='25' maxsize='50' value='$hae_item_name' READONLY>" );

    $standard_blurb = "<b>"._("Warning:")."</b> "._("Deletion is permanent and cannot be undone.")."<br> "._("This script does not check that folders are empty.");

    echo "<div style='border: 1px solid grey; margin-left: .5em; padding: .25em;'>\n";
    echo "<p style='margin-top: 0em;'>$standard_blurb</p>";
    echo "<form style='margin: 0em;' action='?' method='POST' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='action' value='delete'>\n";
    echo "<input type='hidden' name='cdrp' value='$hae_curr_relpath'>\n";
    echo "<b>$question</b>";
    echo "<input type='submit' value='" . _("Delete") ."'>\n";
    echo "</form>\n";
    echo "</div>\n";

    showReturnLink();
    theme("", "footer");

// -----------------------------------------------------------------------------
} else if ($action == 'delete') {

    $item_name = @$_POST['del_file'];
    confirmIsLocal('FD', $item_name);

    $src_path = "$curr_abspath/$item_name";

    $trash_path = "$uploads_dir/TRASH";
    // XXX Create $trash_path if it doesn't exist yet?

    // Use time() to avoid destination collisions
    $dst_path = $trash_path."/".time()."_".$item_name;

    // For safety, we move the item into TRASH and let the
    // existing cron job remove it instead of using unlink()
    $result = @rename($src_path, $dst_path);

    // Handle errors
    if (!$result) {
        fatalError( sprintf(_("Unable to move %s to TRASH folder."), hce($item_name)) );
    }

    showMessage('info', sprintf(_("%s has been moved to the TRASH folder for deletion."), hce($item_name)));
    showReturnLink();

// -----------------------------------------------------------------------------
} else {
    // no matching $action in input
    fatalError( sprintf(_("Invalid action: '%s'"), hce($action)) );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function dpscans_access_mode($username) {
    $userSettings =& Settings::get_settings($username);
        return $userSettings->get_value("dpscans");
}

// Function for displaying directory contents
function showContent() {
    global $curr_relpath, $hae_curr_relpath, $curr_abspath, $hce_curr_displaypath;

    $item_names = getDirectoryItemsSorted();

    if ($item_names === FALSE) return; // XXX fatalError(_("Unable to open folder")) ?

    // Style for directory listing table
    echo "
    <style type='text/css'>
        .dirlist { border: 1px solid black; width: 100%; margin: .5em; background-color: #EEEEEE; }
        .dirlist th { font-family: sans-serif; font-variant: small-caps; text-align: left; }
        .dirlist td { font-size: 90%; height: 1em; line-height: 100%; border-bottom: 1px dotted lightgrey; padding-right: 3em; }
        .dirlist th.actions { font-family: sans-serif; text-align: center!important; padding: 0 1 0 1em; }
        .dirlist caption { font-size: 111%; margin-top: .5em; border: 1px solid black; }
    </style>
    ";

    // Start table and headers
    $caption_text = sprintf(_("Directory listing for <b>%s</b>"), $hce_curr_displaypath);
    $actions_text = _("Actions");
    echo "
    <table class='dirlist'>
        <caption style='background-color: #e0e8dd;'>$caption_text</caption>
        <tr style='background-color: #DDDDDD;'>
            <th style='text-align:center;' title='Additional Features to be added here'>$actions_text</th>
            <th>Name</th>
            <th>Time</th>
            <th>Size</th>
        </tr>
    ";

    // Always put the "up" entry at the top.
    if ( str_contains($curr_relpath,'/') )
    {
        $parent_relpath = dirname($curr_relpath);
        $url = "?cdrp=" . urlencode($parent_relpath);
        $text = _("up one level");
        echo "
            <tr>
                <th></th>
                <td colspan='3'><a href='$url'>$text</a></td>
            </tr>
        ";
    }

    foreach ( $item_names as $item_name )
    {
        $hce_item_name = hce($item_name);

        $item_path = "$curr_abspath/$item_name";

        if (is_file($item_path))
        {
            $actions_blurb =
                getActionsBlock( $item_name, array('showdelete', 'download', 'showrename', 'showmove') );

            echo "
            <tr>
                <th class='actions'>$actions_blurb</th>
                <td><img src='wfb_images/wfb_file.gif'>&nbsp;$hce_item_name</td>
                <td align='left'><span style='font-family: monospace!important;'>".date ('d-M-Y H:i:s', filemtime($item_path))."</span></td>
                <td align='right'>".formatFileSize(filesize($item_path))."</td>
            </tr>
            ";
        } elseif (is_dir($item_path)) {
            $actions_blurb =
                getActionsBlock( $item_name, array('showdelete', 'showrename') );
            $url = "?cdrp=" . urlencode("$curr_relpath/$item_name");
            echo "
            <tr>
                <th class='actions'>$actions_blurb</th>
                <td colspan='3'><img src='wfb_images/wfb_directory.gif'>&nbsp;<a href='$url'>$hce_item_name&#47;</a></td>
            </tr>
            ";
        }
    }
    echo "</table>\n";
}

function getDirectoryItemsSorted()
{
    global $curr_abspath;

    $handle = @opendir($curr_abspath);
    if ($handle === FALSE) return FALSE;

    $item_names  = array();
    $items_files = array();
    $items_dirs  = array();
    while ( ($item_name = readdir($handle)) !== FALSE ) {
        if ($item_name == "." || $item_name == "..") continue;

	$curr_entry = $curr_abspath."/".$item_name;
	if (is_dir($curr_entry))
	{
	    $items_dirs[] = $item_name;
	}
	else if (is_file($curr_entry))
	{
	    $items_files[] = $item_name;
	}
    }
    closedir($handle);
    sort($items_dirs);
    sort($items_files);
    $item_names = array_merge( $items_dirs, $items_files );

    return $item_names;
}

function getActionsBlock( $item_name, $valid_actions )
{
    global $hae_curr_relpath;
    $hae_item_name = hae($item_name);

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

// Prettyprinting for filesizes
function formatFileSize($sizeInBytes, $precision=1) {
    if ($sizeInBytes < 1024) {
        $formatted_size = $sizeInBytes;
        $formatted_unit = "b";
    } else {
        $k = intval($sizeInBytes/1024);
        if ($k < 1024) {
            $formatted_size = $k;
            $formatted_unit = "k";
        } else {
            $m = number_format((($sizeInBytes/1024) / 1024), $precision);
            $formatted_size = $m;
            $formatted_unit = "M";
        }
    }
    return $formatted_size . "&nbsp;<span style='font-family: monospace!important;'>$formatted_unit</span>";
}

function confirmIsLocalFile($filename)
// If $filename is a valid filename parameter,
// and names a file in the current directory, return.
// Otherwise, print an error message and exit.
{
    confirmIsLocal('F', $filename);
}

function confirmIsLocal($type, $item_name)
{
    global $curr_abspath, $hce_curr_displaypath;

    assert( $type == 'F' || $type == 'D' || $type == 'FD' );

    if ( $item_name == '' ) {
        fatalError( _("Item name must not be empty.") );
    }

    if ( strpos($item_name, '/') !== FALSE ) {
        fatalError( _("Item name must not contain a slash character") );
    }

    $src_path = "$curr_abspath/$item_name";

    // Note that 'file_exists', despite the name, doesn't require
    // that its arg identify a file (as opposed to a directory).
    if (!file_exists($src_path)) {
        fatalError( sprintf(_("folder %s does not have an item named %s"), $hce_curr_displaypath, hce($item_name)) );
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
        fatalError( sprintf($msg, hce($item_name)) );
    }
}

function fatalError($message) {
    showMessage('error', $message);

    if (isset($GLOBALS['curr_relpath'])) {
        showReturnLink();
    } else {
        showHomeLink();
    }

    if (isset($GLOBALS['page_title'])) {
        // This is a page that calls theme(),
        // so generate the footer before exiting.
        theme('', 'footer');
    }

    exit();
}

// Display a formatted informational or error message
function showMessage($type, $message) {
    if ($type == 'error') {
        $prefix = _("ERROR:");
        $style = "color: red;";
    } else {
        $prefix = _("INFO:");
        $style = "background-color: lightgreen; color: black;";
    }
    $style .= " border: 1px solid black; padding: .5em; margin: 0.8em 0;";
    echo "<div style='$style'><b>$prefix</b> $message</div>\n";
}

// Display a return link (to the 'showdir' view)
function showReturnLink() {
    global $curr_relpath, $hce_curr_displaypath;
    $url = "?cdrp=" . urlencode($curr_relpath);
    $text = sprintf(_("Return to folder %s"), $hce_curr_displaypath);
    echo "<p><a href='$url'>$text</a></p>\n";
}

function showHomeLink() {
    $text = sprintf(_("Return to your home folder"));
    echo "<p><a href='?action=showdir'>$text</a></p>\n";
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

function showCaveats() {
    echo "<style type='text/css'>\nli { line-height:100%!important; font-size:90%; }\n</style>\n";
    echo "<p><b>Current Conditions:</b></p>\n";
    echo "<ul>\n";
    echo "<li>You <b>can</b> upload files into your user folder. <i>Files are tested for validity and AV scanned.</i></li>\n";
    echo "<li>You <b>can</b> download files from your user folder.</li>\n";
    echo "<li>You <b>can</b> delete files in your user folder.</li>\n";
    echo "<li>You <b>can</b> delete folders in your user folder.</li>\n";
    echo "<li>You <b>can</b> transfer files to other dpscans users.</li>\n";
//    This is probably not an issue, as Commons users can just give the file to whoever needs it
//    echo "<li>PM/PF/SA <b>can not</b> access the non-PM/PF/SA 'Commons' upload area.(1)</li>\n";
    echo "<li>You <b>can</b> browse into subfolders.</li>\n";
    echo "<li>You <b>can</b> create subfolders.</li>\n";
    echo "<li>You <b>can</b> rename files and folders.</li>\n";
    echo "<li>No, there is still not an upload progress bar.</li>\n";
    echo "</ul><hr><ul>\n";
    echo "<li><i>The list items above are statements of feature status, not permissions</i>.</li>\n";
    echo "<li>A number after a statement is a tentative implementation priority.</li>\n";
    echo "<li>A statement after a number is probably a line from a BASIC program.</li>\n";
    echo "<li>Anything else you don't see probably isn't there either.</li>\n";
    echo "</ul>\n";
}


// Move these to misc.inc?

function hce($string)
// "hce" stands for "HTML Content Encode"
// i.e., Encode $string for inclusion in/as the content of an HTML element.
{
    return htmlspecialchars($string, ENT_NOQUOTES);
}

function hae($string)
// "hae" stands for "HTML Attribute Encode"
// i.e., Encode $string for inclusion in/as the value of an HTML attribute.
// This is like attr_safe(), but it *does* convert '&' to '&amp;'.
{
    return str_replace( 
        array("&", "'", "\""), array("&amp;", "&#39;", "&quot;"), $string);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// unused...

// TODO: Function to sanitize filenames to prevent possible directory traversals
//
// From OWASP.org:
// (http://www.owasp.org/index.php/Unrestricted_File_Upload)
// All the control characters and Unicode ones should be removed from the filenames
// and their extensions without any exception. Also, the special characters such as
// ";:></\", additional ".*%$", and so on should be
// discarded as well. If it is applicable and there is no need to have Unicode
// characters, it is highly recommended to only accept Alpha-Numeric characters
// and only 1 dot as an input for the file name and the extension; in which the
// file name and also the extension should not be empty at all.
//
// Regular expression: [a-zA-Z0-9]{1,200}\.[a-zA-Z0-9]{1,10}
//
// NOTE: We should probably allow hyphen and underscore. (donovan)

function sanitizeFilename($name) {
    // TODO
    return $name;
}

// vim: sw=4 ts=4 expandtab
?>

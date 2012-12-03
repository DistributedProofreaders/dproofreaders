<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'misc.inc'); // get_upload_err_msg()


# Directory structure under uploads dir
$trash_dir       = "$uploads_dir/TRASH";

$commons_rel_dir = "Commons";
$commons_dir     = "$uploads_dir/$commons_rel_dir";

$users_rel_dir   = "Users";
$users_dir       = "$uploads_dir/Users";

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
// The user's dpscans setting takes priority:
// * 'common' means the user gets access to the Commons directory,
// * 'self' means they get their own home directory
// * 'disabled' means they have no upload access.
// Otherwise, the default policy is that PMs, PFs & SAs
// get their own home dir, and other users have no upload access.
$access_mode = dpscans_access_mode($pguser);
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
    $page_title = sprintf(_("Manage your %s folder"), hce($uploads_account));
    theme($page_title, "header");
    echo "<h1>$page_title</h1>\n";
    echo "<p>" . _("Your user permissions do not allow access to this script.") . "</p>";
    echo "<p>" . sprintf(_("If you are a Content Provider, please email db-req with the subject '%s access request' and request access to the 'common' %s area in the body of your message."), hce($uploads_account), hce($uploads_account)) . "</p>";
    echo "<p>" . sprintf(_("If you are a Missing Pages Provider, please email db-req with the subject '%s access request' and request 'self' access to %s."), hce($uploads_account), hce($uploads_account)). "</p>";
    theme("", "footer");
    exit;
}

// Access predicates
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
    global $commons_rel_dir, $users_rel_dir;

    // Users may move files to the commons directory
    if ($dir == $commons_rel_dir) return True;

    // Users may not move files anywhere else except the Users dir.
    if (strpos($dir, "$users_rel_dir/") !== 0) return False;
    $rel = substr($dir, strlen("$users_rel_dir/"));

    // Users may only move files to a user's home directory root
    // and not a subdirectory of it.
    return strpos($rel, "/") === False;
}


// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

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

$cdrp_sanitized = canonicalize_path($cdrp);
if ($cdrp_sanitized === False) fatalError( _("Invalid cdrp"));

// XXX
// Do we reject any cdrp that isn't in normalized form?
if (TRUE) {
    if ($cdrp_sanitized != $cdrp) {
        fatalError( _("cdrp was not in normalized form") );
    }
}

// Only SAs are allowed access other home folders.
if (!user_may_access_all_upload_dirs()) {
    if (!startswith("$cdrp_sanitized/", "$home_dirname/")) {
        fatalError( _("You are restricted to your home folder and its descendants") );
    }
}

// Finally, does the specified directory actually exist?
if (!is_dir("$uploads_dir/$cdrp_sanitized")) {
    // (It's possible a user could get this without URL-tweaking,
    // if they deleted a directory but still had an old directory listing
    // in another browser window.)
    fatalError( sprintf(_("'%s' does not exist, or is not a folder"), hce($cdrp_sanitized)) );
}

// Yay, cdrp has passed all the tests!

$curr_relpath = $cdrp_sanitized;

$curr_abspath = "$uploads_dir/$curr_relpath";
// The absolute path of the current directory.

$curr_displaypath = "~$uploads_account/$curr_relpath";
// A "nice" representation of the absolute path,
// one that doesn't reveal details of the upper reaches of the filesystem.

// For convenience, here are a couple of encoded forms:
$hae_curr_relpath = hae($curr_relpath);
$hce_curr_displaypath = hce($curr_displaypath);

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX


// Decide what to do based on the action parameter

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

switch ($action) {
    case 'showdir':    do_showdir();    break;
    case 'showupload': do_showupload(); break;
    case 'upload':     do_upload();     break;
    case 'showmkdir':  do_showmkdir();  break;
    case 'mkdir':      do_mkdir();      break;
    case 'showrename': do_showrename(); break;
    case 'rename':     do_rename();     break;
    case 'showmove':   do_showmove();   break;
    case 'move':       do_move();       break;
    case 'download':   do_download();   break;
    case 'showdelete': do_showdelete(); break;
    case 'delete':     do_delete();     break;
    default:
        // no matching $action in input
        fatalError(sprintf(_("Invalid action: '%s'"), hce($action)));
        break;
}

function do_showdir()
{
    global $curr_relpath, $hce_curr_displaypath;
    global $uploads_account, $pguser, $home_dir_created, $autoprefix_message;

    $page_title =  sprintf( _("Manage folder %s"), $hce_curr_displaypath );
    theme($page_title, "header");
    echo "<h1>$page_title</h1>\n";

    // If we created a directory for the user, assume this is their first visit
    // and display an informational message - down here after the regular headers
    // are sent, which is why a flag for this exists above.

    if ( $home_dir_created ) {
        showMessage('info', sprintf(_("Home folder created for user %s."), hce($pguser)));
    }

    echo "<p>" . sprintf(_("This page allows you to manage content in this %s folder.<br>Additional file management features are gradually being added. See Current Conditions, below."), hce($uploads_account)) . "</p>\n";

    if (dpscans_access_mode($pguser) == 'common') {
        showMessage('info', _("Because you are not a PM, your files are located in a common, shared area.<br><u>Please take care to avoid affecting other users' files.</u>"));
        showMessage('info', $autoprefix_message);
    }

    showForm(
        'showupload',
        $curr_relpath,
        _("Click the button to upload a file to this folder:"),
        _("Upload a File")
    );

    showForm(
        'showmkdir',
        $curr_relpath,
        _("Click the button to create a new subfolder:"),
        _("Create a Subfolder")
    );

    // Display the directory listing
    showContent();

    // Display Caveats about use on this "main" page only
    showCaveats();

    theme("", "footer");
}

function do_showupload()
{
    global $curr_relpath, $hce_curr_displaypath;
    global $pguser, $autoprefix_message;

    $standard_blurb = _("<b>Note:</b> Please make sure the file you upload is Zipped (not Gzip, TAR, etc.).<br> The file should have the .zip extension, NOT .Zip, .ZIP, etc.<br>The rest of the file's name must consist of ASCII letters, digits, underscores, and/or hyphens. It must not begin with a hyphen.");
    $submit_blurb = _("After you click the '%s' button, the browser will appear to be slow getting to the next page. This is because it is uploading the file.");

    $page_title =  sprintf( _("Upload a file to folder %s"), $hce_curr_displaypath );
    theme($page_title, "header");
    echo "<h1>$page_title</h1>\n";

    $form_content = "";
    if (dpscans_access_mode($pguser) == 'common') {
        $form_content .= getMessage('info', $autoprefix_message);
    }
    $form_content .= "<p style='margin-top: 0em;'>$standard_blurb</p>\n";
    $form_content .= _("File to upload:") . "&nbsp;";
    $form_content .= "<input type='file' name='the_file' size='25' maxsize='50'>";

    showForm(
        'upload',
        $curr_relpath,
        $form_content,
        _("Upload")
    );

    // Display the users directory listing
//    showContent();

    showReturnLink();
    theme("", "footer");
}

function do_upload()
{
    global $curr_abspath, $hce_curr_displaypath, $testing;
    global $pguser, $despecialed_username;

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

    if (!is_valid_filename($file_info['name'], "zip")) {
        fatalError( _("Invalid filename.") );
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
    $zip_retval = 0;

    // /usr/bin/file
    // -b: brief output
    // -i: input file
    // --: don't parse any further arguments starting with -/-- as options
    $cmd = "/usr/bin/file -b -i -- " . escapeshellcmd($temporary_path);
    exec($cmd, $zip_test_result, $zip_retval);
    list($file_type) = explode(';', $zip_test_result[0], 2);
    if ($file_type == 'application/x-zip' ||
        $file_type == 'application/zip') {
        showMessage('info', _("OK: Valid zip file."));
    } else {
        fatalError( _("File is not a valid zip file: removing it.") );
    }
    // XXX /usr/bin/file only looks at the first few bytes of the file.
    // Maybe we should check the whole file's integrity with 'unzip -t'.

    // Anti-virus check: perform 'clamscan <FILENAME>' and expect return value = 0
    $av_test_result = array();
    $av_retval=0;

    // /usr/bin/clamscan
    // --: don't parse any further arguments starting with -/-- as options
    $cmd = "/usr/bin/clamscan -- " . escapeshellcmd($temporary_path);
    exec($cmd, $av_test_result, $av_retval);
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
    $target_name = $file_prefix . $file_info['name'];
    $target_path = "$curr_abspath/$target_name";

    // XXX
    // If there's already something at $temporary_path,
    // this will silently overwrite it.
    // That might or might not be the user's intent.
    if (! @move_uploaded_file($temporary_path, $target_path) ) {
        fatalError( _("Webserver failed to copy uploaded file from temporary location to upload folder.") );
    }

    echo "<p>" . sprintf(_("File %s successfully uploaded to folder %s."), hce($target_name), $hce_curr_displaypath), "</p>\n";

    // Log the file upload
    // In part so that we can possibly clean up with some automation later
    $reporting_string = "DPSCANS: File uploaded to " . $target_path;
    error_log($reporting_string);

    showReturnLink();
}

function do_showmkdir()
{
    global $curr_relpath, $hce_curr_displaypath;

    $page_title =  sprintf( _("Create a subfolder in folder %s"), $hce_curr_displaypath );
    theme($page_title, "header");
    echo "<h1>$page_title</h1>\n";

    $form_content = _("Name of subfolder to create:") ."&nbsp;<input type='text' name='new_dir_name' size='25' maxsize='50'>";
    showForm(
        'mkdir',
        $curr_relpath,
        $form_content,
        _("Create")
    );

    showReturnLink();
    theme("", "footer");
}

function do_mkdir()
{
    global $curr_abspath;

    $new_dir_name = @$_POST['new_dir_name'];

    if (!is_valid_filename($new_dir_name)) {
        fatalError( _("Invalid folder name.") );
    }

    // XXX For 'common' users, are new subfolders auto-prefixed with their username?

    $new_dir_abspath = "$curr_abspath/$new_dir_name";
    if ( file_exists($new_dir_abspath) ) {
        fatalError( sprintf(_("%s already exists"), hce($new_dir_name)) );
        // hce isn't needed when is_valid_filename()is so bland,
        // but the pattern could change.
    }

    if (!mkdir($new_dir_abspath)) {
        fatalError( sprintf(_("Unable to create folder")) );
    }

    showMessage('info', sprintf(_("Created folder %s"), hce($new_dir_name)));
    showReturnLink();
}

function do_showrename()
{
    global $curr_relpath;

    $page_title =  _("Rename an item");
    theme($page_title, "header");
    echo "<h1>$page_title</h1>\n";

    $item_name = @$_POST['item_name'];
    confirmIsLocal('FD', $item_name, TRUE);

    $form_content = "<input type='hidden' name='item_name' value='" . hae($item_name) . "'>\n";
    $form_content .= sprintf(
        _("Rename %s as %s"),
        hce($item_name),
        "<input type='text' name='new_item_name' size='25'>"
    );

    showForm(
        'rename',
        $curr_relpath,
        $form_content,
        _("Rename")
    );

    showReturnLink();
    theme("", "footer");
}

function do_rename()
{
    global $curr_abspath;

    $item_name = @$_POST['item_name'];
    confirmIsLocal('FD', $item_name);

    $item_path = "$curr_abspath/$item_name";

    $new_item_name = @$_POST['new_item_name'];

    if (!is_valid_filename($new_item_name)) {
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
        fatalError(sprintf(_("%s already exists"), hce($new_item_name)));
    }

    if (!@rename($item_path, $new_item_path)) {
        fatalError( sprintf(_("Unable to rename item %s as %s."), hce($item_name), hce($new_item_name)) );
    }

    showMessage('info', sprintf(_("Item %s has been renamed as %s."), hce($item_name), hce($new_item_name)));
    showReturnLink();
}

function do_showmove()
{
    global $uploads_dir, $commons_dir, $users_dir, $curr_abspath, $curr_relpath;

    // NOTE: 'move' is a special case of 'rename' and could be coded as such
    // However, since we only want to allow the user to move a file to a valid
    // user directory, we should probably generate a <SELECT><OPTION>... control
    // from the names of directories in /home/dpscans/

    $page_title =  _("Move a file to another user's folder");
    theme($page_title, "header");
    echo "<h1>$page_title</h1>\n";

    // Get an array of all directory names in the Users directory
    // This is used to identify the "target user"
    // (Which really means that user's directory).
    $valid_target_dirs = searchdir($users_dir, 1, "DIRS");
    // Remove first element (which is $uploads_dir itself)
    unset($valid_target_dirs[0]);
    // Allow users to tranfer files to Commons too
    array_unshift($valid_target_dirs, $commons_dir);

    $item_name = @$_POST['item_name'];
    confirmIsLocalFile($item_name);

    $form_content  = "<p>"._("Select the folder of the user who should receive this file:")."&nbsp;";
    $form_content .= "<select name='target_dir'>\n";

    foreach($valid_target_dirs as $full_dir) {
        // Don't display the current directory, it's not a valid target
        if ($full_dir == "$curr_abspath/") continue;
        $dir = rtrim(substr($full_dir, strlen("$uploads_dir/")), '/');

        $form_content .= "<option value='" . hae($dir) . "'>" . hce($dir) . "</option>\n";
    }
    $form_content .= "</select>\n";
    $form_content .= "<p><b>" . sprintf(
        _("Are you sure you want to move&nbsp;%s&nbsp;?"),
        "<input type='text' name='item_name' size='25' maxsize='50' value='" . hae($item_name) . "' READONLY>"
    ) . "</b>";

    echo "<p><b>"._("Warning:")."</b> "._("Moving a file to another user cannot be undone.")."</p>";
    showForm(
        'move',
        $curr_relpath,
        $form_content,
        _("Move File")
    );


    showReturnLink();
    theme("", "footer");
}

function do_move()
{
    global $uploads_dir, $curr_abspath, $curr_relpath;

    $item_name = @$_POST['item_name'];
    confirmIsLocalFile($item_name);

    $src_path = "$curr_abspath/$item_name";

    $dst_dir_relpath = canonicalize_path(trim(@$_POST['target_dir'], '/'));

    if ($dst_dir_relpath === False || !is_valid_move_destination($dst_dir_relpath)) {
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

    if (!@rename($src_path, $dst_path)) {
        fatalError( sprintf(_("Unable to move file %s to destination folder: %s."), hce($item_name), hce($dst_dir_relpath)) );
    }

    showMessage('info', sprintf(_("File %s has been moved to folder %s"), hce($item_name), hce($dst_dir_relpath)));
    showReturnLink();
}

function do_download()
{
    global $curr_abspath;

    $item_name = @$_POST['item_name'];
    confirmIsLocalFile($item_name);

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
        fatalError( _("Unable to send file") );
    }
}

function do_showdelete()
{
    global $curr_abspath, $curr_relpath, $hce_curr_displaypath;

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

    $form_content  = "<p style='margin-top: 0em;'><b>"._("Warning:")."</b> ";
    $form_content .= _("Deletion is permanent and cannot be undone.")."<br> ";
    $form_content .= _("This script does not check that folders are empty. ");
    $form_content .= "<b>" . sprintf( $question_template,
        "<input type='text' name='del_file' size='25' maxsize='50' value='" . hae($item_name) . "' READONLY>" ) . "</b>";

    showForm(
        'delete',
        $curr_relpath,
        $form_content,
        _("Delete")
    );

    showReturnLink();
    theme("", "footer");
}

function do_delete()
{
    global $curr_abspath, $trash_dir;

    $item_name = @$_POST['del_file'];
    confirmIsLocal('FD', $item_name);

    $src_path = "$curr_abspath/$item_name";

    // XXX Create $trash_path if it doesn't exist yet?

    // Use time() to avoid destination collisions
    $dst_path = $trash_dir."/".time()."_".$item_name;

    // For safety, we move the item into TRASH and let the
    // existing cron job remove it instead of using unlink()
    if (!@rename($src_path, $dst_path)) {
        fatalError( sprintf(_("Unable to move %s to TRASH folder."), hce($item_name)) );
    }

    showMessage('info', sprintf(_("%s has been moved to the TRASH folder for deletion."), hce($item_name)));
    showReturnLink();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function dpscans_access_mode($username) {
    $userSettings =& Settings::get_settings($username);
        return $userSettings->get_value("dpscans");
}

// Function for displaying directory contents
function showContent() {
    global $curr_relpath, $hae_curr_relpath, $curr_abspath, $hce_curr_displaypath;

    $item_names = getDirectoryItemsSorted($curr_abspath);

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
                <td align='right'><span style='font-family: monospace!important;'>".humanizeBytes(filesize($item_path))."</span></td>
            </tr>
            ";
        } elseif (is_dir($item_path)) {
            $actions_blurb =
                getActionsBlock( $item_name, array('showdelete', 'showrename') );
            // If we're in the root directory, subdir paths must be 'foo', not '/foo' to be canonical
            if ($curr_relpath == "") {
                $url = "?cdrp=" . urlencode($item_name);
            } else {
                $url = "?cdrp=" . urlencode("$curr_relpath/$item_name");
            }

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

function getDirectoryItemsSorted($curr_abspath)
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

// Represent $n bytes as a  $m kB-- $m PB string
function humanizeBytes($n)
{
    $fmt   = "%d B";
    $units = array("PB", "TB", "GB", "MB", "kB");
    while($n >= 1024) {
        $n   /= 1024.0;
        $fmt  = "%.2f " . array_pop($units);
    }
    return sprintf($fmt, $n);
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

    // NB this catches $item_name == NULL too
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

// return or echo a formatted informational or error message
function getMessage($type, $message) {
    if ($type == 'error') {
        $prefix = _("ERROR:");
        $style = "color: red;";
    } else {
        $prefix = _("INFO:");
        $style = "background-color: lightgreen; color: black;";
    }
    $style .= " border: 1px solid black; padding: .5em; margin: 0.8em 0;";
    return "<div style='$style'><b>$prefix</b> $message</div>\n";
}

function showMessage($type, $message) {
    echo getMessage($type, $message);
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
function showForm($action, $cdrp, $form_content, $submit_label)
{
    // Display a div with a form containing action and cdrp hidden inputs; some content,
    // which can be abritrary HTML/other inputs; and finally a labeled submit button
    echo "<div style='border: 1px solid grey; margin-left: .5em; padding: .25em;'>\n";
    echo "<form style='margin: 0em;' action='?' method='POST' enctype='multipart/form-data'>\n";
    echo "<input type='hidden' name='action' value='" . hae($action) . "'>\n";
    echo "<input type='hidden' name='cdrp' value='" . hae($cdrp) . "'>\n";
    echo "$form_content&nbsp;<input type='submit' value='$submit_label'>\n";
    echo "</form>\n";
    echo "</div>\n";
}

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
        $regexp = '/^[a-zA-Z_0-9][a-zA-Z_0-9-]{0,200}\.' . $restrict_extension . '$/';
    }

    // The filename is valid if the regexp matches exactly once.
    return preg_match($regexp, $filename) == 1;
}

// vim: sw=4 ts=4 expandtab
?>

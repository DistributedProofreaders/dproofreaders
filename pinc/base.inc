<?php
// This file sets up the vital infrastructure for the site, including:
// * including site_vars.php to set global variables
// * creating uncaught exception handler
// * connecting to the database
// * (re)establishing the session
// * setting up gettext
// * enforcing maintenance mode
// * providing the require_login() function
//
// This file should be included at the top of EVERY .php page. This
// is not a catch-all. The kitchen sink does not belong in here!
// Keeping this and its dependencies slim improves overall page
// performance.
//
// The order of the include_once()s in this file is important!

// Override $relPath to be the absolute path to the directory that
// contains this file. This allows registered shutdown functions
// to know where to find files (working directory of the script
// can change inside the shutdown function based on PHP docs).
// This also helps things like APC which can bypass stat calls
// on includes that use absolute paths.
$relPath=dirname(__FILE__)."/";

include_once($relPath.'site_vars.php');

// If we're not testing, whitewash any uncaught exceptions
if(!$testing)
{
    function production_exception_handler($exception)
    {
        echo "<br>\n";
        echo $exception->getMessage();
    }
    set_exception_handler('production_exception_handler');
}

include_once($relPath.'connect.inc');

$db_Connection=new dbConnect();
if ($db_Connection->error != '')
{
    // If we're in maintenance mode, don't die here - we'll more gracefully
    // error out later in abort_if_in_maintenance_mode()
    if(!$maintenance)
        die('Error: Unable to connect to database.');

    $db_Connection=False;
}

include_once($relPath.'dpsession.inc');

// If we don't have a database connection, don't try to resume the session.
if($db_Connection)
    $user_is_logged_in = dpsession_resume();

include_once($relPath.'gettext_setup.inc');
include_once($relPath.'maintenance_mode.inc');

// Check to see if we are in a maintenance mode
abort_if_in_maintenance_mode();

//----------------------------------------------------------------------------

function require_login()
{
    global $user_is_logged_in;
    global $code_url;
    global $relPath;

    // return if the user is already logged in
    if($user_is_logged_in)
        return;

    include_once($relPath.'metarefresh.inc');

    // Remember the current REQUEST_URI, so that we can send the user there
    // after the login process.
    // (urlencode it because it might contain characters [specifically, &]
    // that would confuse parsing of the login.php URL.)
    $request_uri_encoded = urlencode($_SERVER['REQUEST_URI']);
    $url="$code_url/accounts/login.php?destination=$request_uri_encoded";
    $body="<A HREF=\"$url\">" . _("Please Sign In") . "</A>";
    $title = _("Please Sign In");

    metarefresh(0,$url,$title,$body);
}

// vim: sw=4 ts=4 expandtab

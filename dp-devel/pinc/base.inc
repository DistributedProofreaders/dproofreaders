<?php
// This file sets up the vital infrastructure for the site, including:
// * including site_vars.php to set global variables
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
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
include_once($relPath.'dpsession.inc');

$db_Connection=new dbConnect();
if ($db_Connection->error !='')
    die('Error: Unable to connect to database.');

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
    // that would confuse parsing of the signin.php URL.)
    $request_uri_encoded = urlencode($_SERVER['REQUEST_URI']);
    $url="$code_url/accounts/signin.php?destination=$request_uri_encoded";
    $body="<A HREF=\"$url\">" . _("Please Sign In") . "</A>";
    $title = _("Please Sign In");

    metarefresh(0,$url,$title,$body);
}

// vim: sw=4 ts=4 expandtab

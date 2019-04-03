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

// store the current time to calculate total page build time
$PAGE_START_TIME = microtime(true);

include_once($relPath.'site_vars.php');

// Register autoloader
spl_autoload_register('dp_class_autoloader');

undo_all_magic_quotes();

// If we're not testing, whitewash any uncaught exceptions
if(!$testing)
{
    function production_exception_handler($exception)
    {
        echo "<br>\n";
        echo html_safe($exception->getMessage());
    }
    set_exception_handler('production_exception_handler');
}

if(!headers_sent())
{
    header("Content-Type: text/html; charset=$charset");

    // Tell proxies to vary the caching based on the Accept-Language header
    // to ensure others behind the proxy don't inadvertantly get a language
    // they aren't expecting.
    header("Vary: Accept-Language");
}

include_once($relPath.'gettext_setup.inc');
include_once($relPath.'DPDatabase.inc');

try {
    DPDatabase::connect();
} catch (Exception $e) {
    // If we're in maintenance mode, don't die here - we'll more gracefully
    // error out later
    if(!$maintenance)
        die("Error: $e");
}

include_once($relPath.'dpsession.inc');

// If we don't have a database connection, don't try to resume the session.
if(DPDatabase::get_connection())
    $user_is_logged_in = dpsession_resume();

configure_gettext($charset, get_desired_language(), $dyn_locales_dir, $system_locales_dir);

if ($maintenance)
{
    /*
    Including user_is.inc causes a perf impact because it includes
    page_tally.inc, which includes stages.inc - all of which have a
    great deal of overhead. That's a whole lot of work to satisfy a
    rare edge case (that we're in maintenance mode). 
    Until that's resolved, conditionally include it.
    */
    include_once($relPath.'user_is.inc');
    include_once($relPath.'slim_header.inc');

    if (!user_is_a_sitemanager())
    {
        $title = sprintf(_("%s Maintenance"),$site_name);
        $message = _("We are currently performing maintenance on the server.  Please be patient, we should have the site back up and running shortly.");
        if($maintenance_message)
            $message .= "<br>$maintenance_message";
        slim_header($title);
        echo "<h2 style='text-align: center'>";
        if (isset($pguser))
            echo "$pguser ";
        echo "$message</h2>";
        exit();
    }
}

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
    $url="$code_url/accounts/require_login.php?destination=$request_uri_encoded";
    $body="<A HREF=\"$url\">" . _("Please Sign In") . "</A>";
    $title = _("Please Sign In");

    metarefresh(0,$url,$title,$body);
}

// Autoloading functions for DP classes in pinc/
function dp_class_autoloader($class)
{
    global $relPath;
    if(is_file($relPath.$class.".inc"))
    {
        include_once($relPath.$class.".inc");
    }
    elseif(is_file($relPath.$class."Class.inc"))
    {
        include_once($relPath.$class."Class.inc");
    }
}

function undo_all_magic_quotes()
// Detect if magic_quotes_gpc is enabled, and if so strip the slashes from
// all magic-quoted variables to return them to their pre-escaped form.
//
// NOTE: Using this function requires you to do appropriate escaping when
// outputting variables in SQL and HTML. ie:
// Code like the following relies on magic_quotes_gpc being on:
//    $x = $_GET['x'];
//    $res = mysqli_query(DPDatabase::get_connection(), "... WHERE x = '$x'");
// Whereas the following works the same regardless of how magic_quotes_gpc
// is set...
//    undo_all_magic_quotes();
//    $x = $_GET['x'];
//    $res = mysqli_query(DPDatabase::get_connection(),
//               sprintf("... WHERE x = '%s'", mysqli_real_escape_string(DPDatabase::get_connection(), $x))
//    );
{
    static $already_undone = FALSE;

    if (!$already_undone && get_magic_quotes_gpc())
    {
        stripslashes_arr_inplace($_GET);
        stripslashes_arr_inplace($_POST);
        stripslashes_arr_inplace($_COOKIE);
        stripslashes_arr_inplace($_REQUEST);
        // Also the deprecated $HTTP_* vars, but we don't use them.
        // In PHP 4, $_ENV too, but we don't use it.
    }

    $already_undone = TRUE;
}

function stripslashes_arr_inplace(&$value, $key = NULL)
// Run stripslashes on an array that may contain other arrays.
// For space efficiency it changes the values in-place by taking the
// first param as a reference.
{
    if(is_array($value))
        array_walk($value, 'stripslashes_arr_inplace');
    else
        $value = stripslashes($value);

    // no need to return $value as it was passed by reference
}

// vim: sw=4 ts=4 expandtab

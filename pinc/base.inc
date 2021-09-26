<?php
// This file sets up code infrastructure for pages that output HTML.
// ../api/index.php handles similar infrastructure for the JSON API.
//
// This file sets up the vital infrastructure for the site, including:
// * providing exception handlers
// * (re)establishing the session
// * setting up gettext
// * enforcing maintenance mode
// * providing the require_login() function
//
// This file should be included at the top of EVERY .php page that renders
// HTML. Keeping this and its dependencies slim improves overall page
// performance.
//
// The order of the include_once()s in this file is important!

include_once($relPath."bootstrap.inc");

if (!headers_sent()) {
    header("Content-Type: text/html; charset=$charset");

    // Disallow other sites from embedding pages in frames/iframes
    header("Content-Security-Policy: frame-ancestors 'self'", false);
    header("X-Frame-Options: SAMEORIGIN");
    header("X-Content-Type-Options: nosniff");
    if ($use_secure_cookies) {
      header("Strict-Transport-Security: max-age=63072000; includeSubDomains; preload");
    }
}

// If we don't have a database connection, don't try to resume the session.
if (DPDatabase::get_connection()) {
    $user_is_logged_in = dpsession_resume();
}

configure_gettext($charset, get_desired_language(), $dyn_locales_dir, $system_locales_dir);

if ($maintenance && !@$maintenance_override) {
    /*
    Including user_is.inc causes a perf impact because it includes
    page_tally.inc, which includes stages.inc - all of which have a
    great deal of overhead. That's a whole lot of work to satisfy a
    rare edge case (that we're in maintenance mode).
    Until that's resolved, conditionally include it.
    */
    include_once($relPath.'user_is.inc');
    include_once($relPath.'slim_header.inc');

    if (!user_is_a_sitemanager()) {
        $title = sprintf(_("%s Maintenance"), $site_name);
        slim_header($title);
        echo "<h1>$title</h1>";

        $message = _("We are currently performing maintenance on the server.  Please be patient, we should have the site back up and running shortly.");
        echo "<p>$message</p>";

        if ($maintenance_message) {
            echo $maintenance_message;
        }

        exit();
    }
}

//----------------------------------------------------------------------------

function production_exception_handler($exception)
{
    global $maintenance;

    if ($exception instanceof DBConnectionError && !$maintenance) {
        // We output the timestamp in the same format and timezone (UTC) as
        // the MySQL error log (sans microseconds) for easier correlation.
        $error_message = gmstrftime("%FT%TZ") . " " .  $exception->getMessage();

        // Dump the error to the php error log
        error_log("base.inc - $error_message");

        // And output a pretty message for the user. gettext isn't loaded yet
        // (because we need a connection to the DB to get the user's language)
        // so output it in English.
        echo "An error occurred connecting to the database. This is unexpected ";
        echo "as the site is not in maintenance mode. Please try again later.";
        exit(1);
    }

    echo "<br>\n";
    echo html_safe($exception->getMessage());
}

function test_exception_handler($exception)
{
    throw $exception;
}

function require_login()
{
    global $user_is_logged_in;
    global $code_url;
    global $relPath;

    // return if the user is already logged in
    if ($user_is_logged_in) {
        return;
    }

    include_once($relPath.'metarefresh.inc');

    // Remember the current REQUEST_URI, so that we can send the user there
    // after the login process.
    // (urlencode it because it might contain characters [specifically, &]
    // that would confuse parsing of the login.php URL.)
    $request_uri_encoded = urlencode($_SERVER['REQUEST_URI']);
    $url = "$code_url/accounts/require_login.php?destination=$request_uri_encoded";
    metarefresh(0, $url);
}

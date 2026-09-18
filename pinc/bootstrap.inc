<?php
// This file sets up the common infrastructure for the site. It is used for
// both HTML pages (via base.inc) and suitable for API calls as well.
// This file should only include functionality that is common in both.
//
// This file sets up the vital infrastructure for the site, including:
// * including site_vars.php to set global variables
// * registering uncaught exception handler
// * connecting to the database
// * pulling in gettext functions
//
// The order of the include_once()s in this file is important!

// Override $relPath to be the absolute path to the directory that
// contains this file. This allows registered shutdown functions
// to know where to find files (working directory of the script
// can change inside the shutdown function based on PHP docs).
// This also helps things like APC which can bypass stat calls
// on includes that use absolute paths.
$relPath = dirname(__FILE__)."/";

// require login by default unless the page has explicitly said otherwise
if (!@isset($require_login)) {
    $require_login = true;
}

// store the current time to calculate total page build time
$PAGE_START_TIME = microtime(true);

require_once $relPath.'SiteConfig.inc';

// Load the session libraries, but allow base.inc and api/index.php to do
// the start/resume.
include_once($relPath.'dpsession.inc');

// Do a fast check on if the user could possibly be logged in based on if
// they have a session cookie -- if not and login is required, fast-fail.
if ($require_login && !dpsession_is_cookie_set()) {
    require_login_redirect();
}

// Register autoloader
spl_autoload_register('dp_class_autoloader');

// Load composer autoloader
require_once($relPath.'../vendor/autoload.php');

// Exception handlers are defined differently for HTML and the API
set_exception_handler(SiteConfig::get()->testing ? test_exception_handler(...) : production_exception_handler(...));

if (!headers_sent()) {
    // Tell proxies to vary the caching based on the Accept-Language header
    // to ensure others behind the proxy don't inadvertently get a language
    // they aren't expecting.
    header("Vary: Accept-Language");
}

include_once($relPath.'gettext_setup.inc');
include_once($relPath.'DPDatabase.inc');

if (!defined('SKIP_DB_CONNECT')) {
    try {
        DPDatabase::connect();
    } catch (Exception $e) {
        // If we're in maintenance mode, don't die here - we'll more gracefully
        // error out later
        if (!SiteConfig::get()->maintenance) {
            throw $e;
        }
    }
}

// Include misc.inc which contains useful functions that almost 90% of
// the pages use in some way.
include_once($relPath.'misc.inc');

//----------------------------------------------------------------------------

// Autoloading functions for DP classes in pinc/
function dp_class_autoloader(string $class): void
{
    global $relPath;
    if (is_file($relPath.$class.".inc")) {
        include_once($relPath.$class.".inc");
    }
}

/**
 * @param array{
 *            expires?: int,
 *            path?: string,
 *            secure?: bool,
 *            httponly?: bool,
 *            samesite?: 'Lax'|'None'|'Strict'
 *        } $options
 */
function dp_setcookie(string $name, string $value = "", int $expires = 0, $options = []): bool
{
    $defaults = [
        "expires" => $expires,
        "path" => "/",
        "secure" => SiteConfig::get()->use_secure_cookies,
        "samesite" => "Lax",
    ];

    $options = array_merge($defaults, $options);
    return setcookie($name, $value, $options);
}

function require_login_redirect(): void
{
    global $relPath;

    include_once($relPath.'metarefresh.inc');

    // Remember the current REQUEST_URI, so that we can send the user there
    // after the login process.
    // (urlencode it because it might contain characters [specifically, &]
    // that would confuse parsing of the login.php URL.)
    $request_uri_encoded = urlencode($_SERVER['REQUEST_URI']);
    $url = SiteConfig::get()->code_url . "/accounts/require_login.php?destination=$request_uri_encoded";
    metarefresh(0, $url);
}

<?php
// Localization setup
include_once($relPath.'languages.inc');

// Return a locale that best matches the language that the user wants and
// that we have a site translation for. "Best" is tricky, because there are
// multiple ways that a user could indicate (either explicitly or implicitly)
// what language they want a page returned in. We walk through the following
// possibilities in this order:
// * If $_GET['lang'] is set (a page is requested as eg: default.php?lang=sr)
//   and requested language is installed, then use that language and set
//   language cookie so that after clicking on links user would remain in the
//   language. This is useful for external linking to a localised version.
// * If user is logged in, and has set language in user preferences, use that
//   language.
// * If user is not logged in, use language cookie, if there is one.
// * If there is no cookie try to guess an appropriate language from browser's
//   settings. There is no need to set the cookie because browser remains the
//   same.
// * If everything else fails, default to en_US.
function get_desired_language()
{
    // This function maybe called multiple times within a page load.
    // Only do the calculations to determine the locale once.
    static $locale = null;
    if ($locale) {
        return $locale;
    }

    $user = User::load_current();
    // If the below logic changes, update locale/debug_ui_language.php too
    if (isset($_GET['lang']) && lang_code($_GET['lang'])) {
        // User requested a specific language for this page
        $locale = lang_code($_GET['lang']);
        dp_setcookie("language", $locale, time() + 31536000);
    } elseif ($user && $user->u_intlang) {
        // User is logged in and has set their language preference
        $locale = $user->u_intlang;
    } elseif (!$user && isset($_COOKIE['language']) && $_COOKIE['language']) {
        // User is not logged in but we have a language cookie
        $locale = $_COOKIE['language'];
    } elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        // Attempt to guess locale based on browser settings
        $test_locale = get_locale_matching_browser_accept_language($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        if ($test_locale) {
            $locale = $test_locale;
        }
    }

    return get_valid_locale_for_translation($locale);
}

function configure_gettext($charset, $locale, $dyn_locales_dir, $system_locales_dir)
{
    if ($charset == "UTF-8") {
        $locale .= ".$charset";
    }

    // if the LANGUAGE environment variable is set, gettext will use that and
    // ignore whatever is here, so unset it
    putenv("LANGUAGE=");

    $locale = setlocale(LC_ALL, $locale);

    // Specify location of translation tables and choose domain
    $gettext_domain = 'messages';
    if (function_exists('bindtextdomain')) {
        bindtextdomain($gettext_domain, $dyn_locales_dir);
        bindtextdomain("iso_639", $system_locales_dir);
        if (function_exists("bind_textdomain_codeset")) {
            bind_textdomain_codeset($gettext_domain, $charset);
            bind_textdomain_codeset("iso_639", $charset);
        }
        textdomain($gettext_domain);
    }
}

// Get the language (locale) for the specified user.
// $user can be a User object or a username.
// If $user is NULL, the current user's locale is returned.
function get_user_language($user = null)
{
    if (!$user) {
        $locale = get_desired_language();
    } else {
        if ($user instanceof User == false) {
            $user = new User($user);
        }
        $locale = get_valid_locale_for_translation($user->u_intlang);
    }
    return $locale;
}

// Configure gettext for a specific user, useful for sending emails.
function configure_gettext_for_user($user = null)
{
    global $charset, $dyn_locales_dir, $system_locales_dir;

    $locale = get_user_language($user);
    configure_gettext($charset, $locale, $dyn_locales_dir, $system_locales_dir);
}

// If the gettext extension is compiled into PHP, then the function named '_'
// (an alias for 'gettext') will be defined.
// If it's not defined, define it to simply return its argument.
if (! function_exists('_')) {
    function _($str)
    {
        return $str;
    }
}

if (! function_exists('ngettext')) {
    function ngettext($singular, $plural, $number)
    {
        return ($number == 1) ? $singular : $plural;
    }
}

// pgettext allows translation of the same strings that
// mean two different things.
// http://php.net/manual/en/book.gettext.php#89975
if (!function_exists('pgettext')) {
    function pgettext($context, $msgid)
    {
        $contextString = "{$context}\004{$msgid}";
        $translation = dcgettext('messages', $contextString, LC_MESSAGES);
        if ($translation == $contextString) {
            return $msgid;
        } else {
            return $translation;
        }
    }
}

// Return a localized date/time string for a date where $pattern is in ICU
// format (https://unicode-org.github.io/icu/userguide/format_parse/datetime/).
function icu_date($pattern, $timestamp = null, $user = null)
{
    $locale = get_user_language($user);
    if (!$timestamp) {
        $timestamp = time();
    }

    $idf = new IntlDateFormatter($locale, IntlDateFormatter::NONE, IntlDateFormatter::NONE);
    $idf->setPattern($pattern);
    return $idf->format($timestamp);
}

// Return a localized date from a predefined template
function icu_date_template($template, $timestamp = null, $user = null)
{
    $templates = [
        // TRANSLATORS: ICU-formatted date in long form
        "long" => _("EEEE, MMMM d, y"),
        // TRANSLATORS: ICU-formatted date in long form with time
        "long+time" => _("EEEE, MMMM d, y 'at' HH:mm"),
        // TRANSLATORS: ICU-formatted date in short form
        "short" => _("MMM d, y"),
    ];

    return icu_date($templates[$template], $timestamp, $user);
}

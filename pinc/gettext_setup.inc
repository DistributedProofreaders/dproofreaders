<?php
// Localization setup
include_once($relPath.'languages.inc');

/*
If $_GET['lang'] is set (a page is requested as eg: default.php?lang=sr) and
requested language is installed, then use that language and set language cookie
so that after clicking on links user would remain in the language. This is
useful for external linking to a localised version.

If user is logged in, and has set language in user preferences, use that
language.

If user is not logged in, use language cookie, if there is one.

If there is no cookie try to guess an appropriate language from browser's
settings. There is no need to set the cookie because browser remains the same.

If everything else fails, default to English.
*/
function get_desired_language()
{
    global $pguser, $userP;

    // This function maybe called multiple times within a page load.
    // Only do the calculations to determine the desired language once.
    static $intlang = NULL;
    if($intlang)
        return $intlang;

    if (isset($_GET['lang']) && lang_code($_GET['lang'])) {
        $intlang = lang_code($_GET['lang']);
        setcookie("language",$intlang,time()+31536000,"/");
    } else if (isset($userP)  && $userP['u_intlang']){
        $intlang = $userP['u_intlang'];
    } else if (!$pguser &&
               isset($_COOKIE['language']) && $_COOKIE['language']) {
        $intlang=$_COOKIE['language'];
    } else if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $test_locale = \
            get_locale_matching_browser_accept_language($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        if($test_locale)
            $intlang = $test_locale;
    }

    // Fall back to English
    if (!@$intlang || !is_locale_translation_enabled($intlang)) {
        $intlang = "en_EN";
    }

    return $intlang;
}

function configure_gettext($charset, $locale, $dyn_locales_dir, $system_locales_dir)
{
    $locale = setlocale(LC_ALL, $locale);

    // Specify location of translation tables and choose domain
    $gettext_domain = 'messages';
    if ( function_exists('bindtextdomain') )
    {
        bindtextdomain($gettext_domain, $dyn_locales_dir);
        bindtextdomain("iso_639", $system_locales_dir);
        if(function_exists("bind_textdomain_codeset")) {
            bind_textdomain_codeset ($gettext_domain, $charset);
            bind_textdomain_codeset ("iso_639", $charset);
        }
        textdomain($gettext_domain);
    }
}

// If the gettext extension is compiled into PHP, then the function named '_'
// (an alias for 'gettext') will be defined.
// If it's not defined (e.g., on dproofreaders.sourceforge.net),
// define it to simply return its argument.
if (! function_exists('_') )
{
    function _($str) { return $str; }
}

if (! function_exists('ngettext') )
{
    function ngettext($singular, $plural, $number)
    {
        return ($number == 1) ? $singular : $plural;
    }
}

// vim: sw=4 ts=4 expandtab

<?php
$require_login = false;

$relPath = "../pinc/";
include_once($relPath."base.inc");
include_once($relPath."theme.inc");

// This page is intentionally not translated to facilitate debugging.

$title = "UI Language Debugger";
output_header($title, NO_STATSBAR);

$user = User::load_current();

function html_safe_or_default(?string $string, string $default): string
{
    return $string !== null ? html_safe($string) : $default;
}

$detected_language = get_desired_language();
$url_language_safe = html_safe_or_default($_GET['lang'] ?? null, "<i>not set</i>");
$pref_language = $user ? $user->u_intlang : "<i>not set</i>";
$user_logged_in = $user ? $user->username : "<i>not logged in</i>";
if ($pref_language == "") {
    $pref_language = "<i>browser detect</i>";
}
$cookie_language_safe = html_safe_or_default($_COOKIE['language'] ?? null, "<i>not set</i>");
$http_accept_language_safe = html_safe_or_default($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? null, "<i>not set</i>");
$browser_locale_safe = get_locale_matching_browser_accept_language($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? null);

$enabled_languages_safe = implode(", ", array_merge(get_installed_locale_translations("all"), ["en_US"]));
echo <<<PAGE
    <h1>$title</h1>

    <p>This page facilicates debugging UI language selection problems. You maybe
    asked to provide the contents of the list below to developers and/or other
    squirrels to troubleshoot unexpected UI language selection by the code.</p>

    <p>The code looks in multiple places to determine what language to show the
    user. It looks at the following sources in this order before falling back
    to use English (en_US). The first one that matches an enabled UI language
    wins.</p>

    <ul>
        <li>lang parameter: <b>$url_language_safe</b></li>
        <li>user preference: <b>$pref_language</b>
            <ul><li>user logged in: <b>$user_logged_in</b></li></ul>
        </li>
        <li>cookie setting: <b>$cookie_language_safe</b></li>
        <li>browser language detected: <b>$browser_locale_safe</b>
            <ul><li>HTTP_ACCEPT_LANGUAGE: <b>$http_accept_language_safe</b></li></ul>
        </li>
        <li>final language used: <b>$detected_language</b></li>
    </ul>

    <p>Enabled UI languages: $enabled_languages_safe.</p>

    PAGE;

if (user_is_a_sitemanager()) {
    $gettext_domain = 'messages';
    $env_lang_safe = html_safe_or_default(getenv("LANG"), "<i>not set</i>");
    $env_language_safe = html_safe_or_default(getenv("LANGUAGE"), "<i>not set<i/>");
    $locale = $detected_language;
    $dyn_locales_dir = SiteConfig::get()->dyn_locales_dir;
    $system_locales_dir = SiteConfig::get()->system_locales_dir;
    $set_locale = setlocale(LC_ALL, "$locale.UTF-8");
    $bind1 = bindtextdomain($gettext_domain, $dyn_locales_dir);
    $bind2 = bindtextdomain("iso_639", $system_locales_dir);
    $bind3 = bind_textdomain_codeset($gettext_domain, 'UTF-8');
    $bind4 = bind_textdomain_codeset("iso_639", 'UTF-8');
    $textdomain = textdomain($gettext_domain);

    echo <<<ADMIN

        <h2>Admin-only</h2>

        <p>This information is only shown to system administrators as it reveals lower-level details about the system.</p>

        <p>env variables:</p>
        <ul>
            <li>LANG: $env_lang_safe</li>
            <li>LANGUAGE: $env_language_safe</li>
        </ul>

        <p>gettext variables:</p>
        <ul>
            <li>charset: <b>UTF-8</b></li>
            <li>dyn_locales_dir: <b>$dyn_locales_dir</b></li>
            <li>system_locales_dir: <b>$system_locales_dir</b></li>
            <li>locale: <b>$locale</b></li>
        </ul>

        <p>gettext function outputs:</p>
        <ul>
            <li>setlocale(LC_ALL, $locale.UTF-8) returned <b>$set_locale</b></li>
            <li>bindtextdomain($gettext_domain, $dyn_locales_dir) returned <b>$bind1</b></li>
            <li>bindtextdomain("iso_639", $system_locales_dir) returned <b>$bind2</b></li>
            <li>bind_textdomain_codeset($gettext_domain, "UTF-8") returned <b>$bind3</b></li>
            <li>bind_textdomain_codeset("iso_639", "UTF-8") returned <b>$bind4</b></li>
            <li>textdomain($gettext_domain) returned <b>$textdomain</b></li>
        </ul>

        ADMIN;
}

echo "<br>";

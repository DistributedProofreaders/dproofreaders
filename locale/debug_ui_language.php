<?php
$relPath="../pinc/";
include_once($relPath."base.inc");
include_once($relPath."theme.inc");

// This page is intentionally not translated to facilitate debugging.

$title = "UI Language Debugger";
output_header($title, NO_STATSBAR);

$user = User::load_current();

$detected_language = get_desired_language();
$url_language = array_get($_GET, 'lang', "<i>not set</i>");
$pref_language = $user ? $user->u_intlang : "<i>not set</i>";
$user_logged_in = $user ? $user->username : "<i>not logged in</i>";
if($pref_language == "") $pref_language = "<i>browser detect</i>";
$cookie_language = array_get($_COOKIE, 'language', "<i>not set</i>");
$http_accept_language = array_get($_SERVER, 'HTTP_ACCEPT_LANGUAGE', "<i>not set </i>");
$browser_locale = get_locale_matching_browser_accept_language(@$_SERVER['HTTP_ACCEPT_LANGUAGE']);

$enabled_languages = implode(", ", array_merge(get_installed_locale_translations("all"), array("en_US")));

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
    <li>lang parameter: <b>$url_language</b></li>
    <li>user preference: <b>$pref_language</b>
        <ul><li>user logged in: <b>$user_logged_in</b></li></ul>
    </li>
    <li>cookie setting: <b>$cookie_language</b></li>
    <li>browser language detected: <b>$browser_locale</b>
        <ul><li>HTTP_ACCEPT_LANGUAGE: <b>$http_accept_language</b></li></ul>
    </li>
    <li>final language used: <b>$detected_language</b></li>
</ul>

<p>Enabled UI languages: $enabled_languages.</p>

PAGE;

if(user_is_a_sitemanager())
{
    $gettext_domain = 'messages';
    $env_lang = getenv("LANG");
    $env_language = getenv("LANGUAGE");
    $locale = $detected_language;
    $set_locale = setlocale(LC_ALL, $locale);
    $bind1 = bindtextdomain($gettext_domain, $dyn_locales_dir);
    $bind2 = bindtextdomain("iso_639", $system_locales_dir);
    $bind3 = bind_textdomain_codeset ($gettext_domain, $charset);
    $bind4 = bind_textdomain_codeset ("iso_639", $charset);
    $textdomain = textdomain($gettext_domain);

    echo <<<ADMIN

<h2>Admin-only</h2>

<p>This information is only shown to system administrators as it reveals lower-level details about the system.</p>

<p>env variables:</p>
<ul>
    <li>LANG: $env_lang</li>
    <li>LANGUAGE: $env_language</li>
</ul>

<p>gettext variables:</p>
<ul>
    <li>charset: <b>$charset</b></li>
    <li>dyn_locales_dir: <b>$dyn_locales_dir</b></li>
    <li>system_locales_dir: <b>$system_locales_dir</b></li>
    <li>locale: <b>$locale</b></li>
</ul>

<p>gettext function outputs:</p>
<ul>
    <li>setlocale(LC_ALL, $locale) returned <b>$set_locale</b></li>
    <li>bindtextdomain($gettext_domain, $dyn_locales_dir) returned <b>$bind1</b></li>
    <li>bindtextdomain("iso_639", $system_locales_dir) returned <b>$bind2</b></li>
    <li>bind_textdomain_codeset($gettext_domain, $charset) returned <b>$bind3</b></li>
    <li>bind_textdomain_codeset("iso_639", $charset) returned <b>$bind4</b></li>
    <li>textdomain($gettext_domain) returned <b>$textdomain</b></li>
</ul>

ADMIN;
}

echo "<br>";

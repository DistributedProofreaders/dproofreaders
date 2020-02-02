<?php
include_once($relPath."languages.inc"); // html_lang_header();
include_once($relPath."misc.inc"); // html_safe(), ends_with()

define('NO_STATSBAR',   False);
define('SHOW_STATSBAR', True);

// This file is used by both theme.inc and slim_header.inc for functions that
// output the HTML header and footer for all pages that show output to the
// user.

// Output the opening HTML page code, including pulling in common styles,
// page title, etc. eg:
function output_html_header($nameofpage, $extra_args = array(), $show_statsbar = True)
{
    global $code_url, $site_abbreviation, $userP;
    global $charset;

    $intlang = get_desired_language();

    // HTML 5 dictates that ISO-8859-1 documents should declare their charset
    // as windows-1252 (which is what web browsers actually treat ISO-8859-1 as).
    if(strcasecmp($charset, 'ISO-8859-1') == 0)
        $output_charset = 'windows-1252';
    else
        $output_charset = $charset;

    echo "<!DOCTYPE html>\n"; // HTML 5
    echo "<html ".lang_html_header($intlang).">\n<head>\n";
    echo "<meta charset='$output_charset'>\n";

    # iOS and Android icons
    echo "<link rel='apple-touch-icon' href='$code_url/graphics/dp-mark-180px-white.png'>\n";
    echo "<link rel='icon' href='$code_url/graphics/dp-mark-180px-white.png'>\n";
    # standard favicon.ico -- this has to be after the rel='icon' above due to
    # a FF bug: https://bugzilla.mozilla.org/show_bug.cgi?id=751712
    echo "<link rel='shortcut icon' href='$code_url/graphics/dp-mark-32px.ico'>\n";

    echo "<title>$site_abbreviation";
    if (isset($nameofpage))
    {
        echo ": ", html_safe($nameofpage);
    }
    echo "</title>\n";

    // Global CSS
    $theme_name = array_get($userP, 'i_theme', "project_gutenberg");
    $css_files = array(
        "$code_url/styles/themes/$theme_name.css",
        "$code_url/styles/layout.css",
        "$code_url/styles/global.css",
    );

    // Statsbar CSS
    if ($show_statsbar)
        $css_files[] = "$code_url/styles/statsbar.css";

    // Per-page CSS
    if (isset($extra_args['css_files']))
        $css_files = array_merge($css_files, $extra_args['css_files']);

    foreach($css_files as $css_file)
    {
        $cache_fixer = get_local_file_browser_cache_key($css_file);
        echo "<link type='text/css' rel='Stylesheet' href='$css_file$cache_fixer'>\n";
    }

    // Any additional style definitions requested by the caller
    if (isset($extra_args['css_data']))
    {
        echo "<style type='text/css'>\n" .
             $extra_args['css_data'] .
             "</style>\n";
    }

    // Global JS
    $js_files = array(
        "$code_url/pinc/3rdparty/jquery/jquery-3.4.1.min.js",
        "$code_url/pinc/3rdparty/xregexp/xregexp-all.js",
    );

    // Per-page JS
    if (isset($extra_args['js_files']))
        $js_files = array_merge($js_files, $extra_args['js_files']);

    foreach($js_files as $js_file)
    {
        $cache_fixer = get_local_file_browser_cache_key($js_file);
        echo "<script src='$js_file$cache_fixer'></script>\n";
    }

    // Per-page Javascript
    if (isset($extra_args['js_data']))
    {
        echo "<script>\n" .
            $extra_args['js_data'] .
            "</script>\n";
    }

    // Any additional head tags
    if(isset($extra_args['head_data']))
    {
        echo $extra_args['head_data'];
    }

    echo "</head>\n\n";
    // framesets don't have <body> elements
    if(!isset($extra_args['frameset']))
    {
        echo "<body";
        if(isset($extra_args['body_attributes']))
            echo " " . $extra_args['body_attributes'];
        echo ">\n\n";
    }
}

// Output HTML page footer to close the page
function output_html_footer($extra_args=array())
{
    // close up the page

    // framesets don't have <body> elements
    if(!isset($extra_args['frameset']))
        echo "\n</body>\n";
    echo "</html>\n";
}

// Browser caches are great, but they are a real PITA when we want to change
// CSS/JS and it's still serving up the older version. This is particularly bad
// when we're doing things like changing page editing interfaces.
// To address that, append the file's timestamp as a query parameter to the file.
// Apache will ignore this but when the value changes the browser will reread
// the file. PHP's statcache should make this fast.
function get_local_file_browser_cache_key($url)
{
    global $code_url, $code_dir;

    $cache_fixer = "";
    if(strpos($url, $code_url) !== FALSE)
    {
        $local_file = $code_dir . substr($url, strlen($code_url));

        // PHP files should never be cached
        if(endswith($local_file, ".php"))
        {
            $timestamp = time();
        }
        else
        {
            $stats = stat($local_file);
            if($stats)
                $timestamp = $stats["mtime"];
        }

        if($timestamp)
            $cache_fixer = "?" . strftime("%Y%m%d%H%M%S", $timestamp);
    }

    return $cache_fixer;
}

// vim: sw=4 ts=4 expandtab

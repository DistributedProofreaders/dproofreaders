<?php
include_once($relPath."languages.inc"); // html_lang_header();

define('NO_STATSBAR', false);
define('SHOW_STATSBAR', true);

// This file is used by both theme.inc and slim_header.inc for functions that
// output the HTML header and footer for all pages that show output to the
// user.

/**
 * Output the opening HTML page code, including pulling in common styles,
 * page title, etc.
 */
function output_html_header($nameofpage, $extra_args = [], $show_statsbar = true)
{
    global $code_url;

    static $was_output = false;
    if ($was_output) {
        return;
    }

    $user = User::load_current();

    // If the user isn't logged in, output a CSRF token for the login and
    // registration form. We need to do this here before we output any other
    // HTML and the login form can appear on multiple pages since it's in
    // the navbar.
    if (!$user) {
        set_csrf_token(false);
    }

    $intlang = get_desired_language();

    echo "<!DOCTYPE html>\n"; // HTML 5
    echo "<html ".lang_html_header($intlang).">\n<head>\n";
    echo "<meta charset='UTF-8'>\n";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";

    // iOS and Android icons
    echo "<link rel='apple-touch-icon' href='$code_url/graphics/dp-mark-180px-white.png'>\n";
    echo "<link rel='icon' href='$code_url/graphics/dp-mark-180px-white.png'>\n";
    // standard favicon.ico -- this has to be after the rel='icon' above due to
    // a FF bug: https://bugzilla.mozilla.org/show_bug.cgi?id=751712
    echo "<link rel='shortcut icon' href='$code_url/graphics/dp-mark-32px.ico'>\n";

    echo "<title>" . SiteConfig::get()->site_abbreviation;
    if (isset($nameofpage)) {
        echo ": ", html_safe($nameofpage);
    }
    echo "</title>\n";

    // Global CSS
    $theme_name = $user ? $user->i_theme : "project_gutenberg";
    $css_files = [
        "$code_url/styles/themes/$theme_name.css",
        "$code_url/node_modules/@fortawesome/fontawesome-free/css/all.min.css",
    ];

    // Statsbar CSS
    if ($show_statsbar) {
        $css_files[] = "$code_url/styles/statsbar.css";
    }

    // Per-page CSS
    if (isset($extra_args['css_files'])) {
        $css_files = array_merge($css_files, $extra_args['css_files']);
    }

    foreach ($css_files as $css_file) {
        $cache_fixer = get_local_file_browser_cache_key($css_file);
        echo "<link type='text/css' rel='Stylesheet' href='$css_file$cache_fixer'>\n";
    }

    // Any additional style definitions requested by the caller
    if (isset($extra_args['css_data'])) {
        echo "<style>\n" .
             $extra_args['css_data'] .
             "</style>\n";
    }

    // Global JS
    $js_files = [
        "$code_url/node_modules/jquery/dist/jquery.min.js",
    ];

    // The translation.js file may not exist
    if (is_file(SiteConfig::get()->dyn_dir . "/locale/translations.js")) {
        $js_files[] = SiteConfig::get()->dyn_url . "/locale/translations.js";
    }

    // Per-page JS
    $js_files = array_merge(
        $js_files,
        $extra_args['js_files'] ?? [],
        $extra_args['js_modules'] ?? []
    );

    $manifest = get_js_manifest();

    $include_runtime = false;
    foreach ($js_files as $js_file) {
        $bundle_filename = $manifest[basename($js_file)] ?? "";
        if ($bundle_filename) {
            $include_runtime = true;
            if (in_array($js_file, $extra_args['js_modules'] ?? [])) {
                echo "<script src='$code_url/$bundle_filename' type='module'></script>\n";
            } else {
                echo "<script src='$code_url/$bundle_filename'></script>\n";
            }
        } else {
            if (in_array($js_file, $extra_args['js_modules'] ?? [])) {
                echo "<script src='$js_file' type='module'></script>\n";
            } else {
                $cache_fixer = get_local_file_browser_cache_key($js_file);
                echo "<script src='$js_file$cache_fixer'></script>\n";
            }
        }
    }
    if ($include_runtime) {
        $runtime_bundle = $manifest["runtime.js"];
        echo "<script src='$code_url/$runtime_bundle' type='module'></script>\n";
        $vendor_bundle = $manifest["vendors.js"];
        echo "<script src='$code_url/$vendor_bundle' type='module'></script>\n";
    }

    // Per-page Javascript
    echo "<script>\n";
    echo "const codeUrl = '$code_url';\n";
    if (isset($extra_args['js_data'])) {
        echo $extra_args['js_data'];
    }
    echo "</script>\n";

    // Any additional head tags
    if (isset($extra_args['head_data'])) {
        echo $extra_args['head_data'];
    }

    echo "</head>\n\n";
    // framesets don't have <body> elements
    if (!isset($extra_args['frameset'])) {
        echo "<body";
        if (isset($extra_args['body_attributes'])) {
            echo " " . $extra_args['body_attributes'];
        }
        echo ">\n\n";
    }
}

/**
 * Output HTML page footer to close the page
 */
function output_html_footer($extra_args = [])
{
    // close up the page

    // framesets don't have <body> elements
    if (!isset($extra_args['frameset'])) {
        echo "\n</body>\n";
    }
    echo "</html>\n";
}

/**
 * Get a browser cache-busting key for a local URL
 *
 * Browser caches are great, but they are a real PITA when we want to change
 * CSS/JS and it's still serving up the older version. This is particularly bad
 * when we're doing things like changing page editing interfaces.
 * To address that, append the file's timestamp as a query parameter to the file.
 * Apache will ignore this but when the value changes the browser will reread
 * the file. PHP's statcache should make this fast.
 */
function get_local_file_browser_cache_key($url)
{
    global $code_url, $code_dir;

    $cache_fixer = "";
    if (strpos($url, $code_url) !== false || strpos($url, SiteConfig::get()->dyn_url) !== false) {
        $local_file = str_replace($code_url, $code_dir, $url);
        $local_file = str_replace(SiteConfig::get()->dyn_url, SiteConfig::get()->dyn_dir, $local_file);

        // PHP files should never be cached
        if (str_ends_with($local_file, ".php")) {
            $timestamp = time();
        } else {
            $stats = stat($local_file);
            if ($stats) {
                $timestamp = $stats["mtime"];
            }
        }

        if ($timestamp) {
            $cache_fixer = "?" . date("YmdHis", $timestamp);
        }
    }

    return $cache_fixer;
}

/**
 * @return string[]
 */
function get_js_manifest(): array
{
    global $code_dir;

    // first, see if the PHP version of our manifest file exists, and if so
    // load it and get the opcache-cached version without hitting the disk
    if (is_file("$code_dir/dist/manifest.php")) {
        require_once("$code_dir/dist/manifest.php");
        return get_cached_js_manifest(); // @phpstan-ignore function.notFound
    }

    // if it doesn't exist, see if the JSON version exists and if so load
    // and process it
    if (is_file("$code_dir/dist/manifest.json")) {
        $raw_manifest = json_decode(file_get_contents("$code_dir/dist/manifest.json"), true) ?? [];
        // webpack puts "auto" in the pathname, no idea why
        foreach ($raw_manifest as $filename => $path) {
            $manifest[$filename] = str_replace("auto", "dist", $path);
        }
        return $manifest;
    }

    // otherwise, just return an empty manifest
    return [];
}

/**
 * @param string[] $manifest
 */
function generate_cached_js_manifest(array $manifest): void
{
    global $code_dir;

    if (!$manifest) {
        return;
    }

    $serialized_manifest = serialize($manifest);
    $cached_php_file = <<<EOF
        <?php
        function get_cached_js_manifest(): array
        {
            return unserialize('$serialized_manifest');
        }
        EOF;

    file_put_contents("$code_dir/dist/manifest.php", $cached_php_file);
}

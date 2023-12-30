<?php

/**
 * Redirect the browser to a new URL
 *
 * If possible it will use HTTP header redirection (via Location:). If that
 * isn't possible, it will use a browser redirect (via a <meta> tag).
 * If this is a test system ($testing=1 in site_vars.php) the redirect
 * will be delayed by 15 seconds if content has already been sent to the
 * client (determined if headers have been sent or if the output buffer
 * has contents in it).
 */
function metarefresh($seconds, $url, $title = "", $body = "", $allow_external = false)
{
    global $testing, $code_url;

    if ($testing && (headers_sent() || ob_get_length())) {
        $sec = $seconds + 15;
        // That may not be long enough to read everything,
        // but it should be long enough to Select All + Copy.
    } else {
        $sec = $seconds;
    }

    // confirm we have an absolute URL
    if (!startswith($url, 'http')) {
        // get the proper protocol
        $absolute_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
        $absolute_url .= $_SERVER['HTTP_HOST'];
        if (!startswith($url, '/')) {
            $absolute_url .= dirname($_SERVER['SCRIPT_NAME']) . "/";
        }
        $absolute_url .= $url;
    } else {
        $absolute_url = $url;
    }

    // If $allow_external is FALSE and the URL isn't local, redirect the
    // user to the homepage.
    if (!$allow_external && !startswith($absolute_url, $code_url)) {
        $absolute_url = "$code_url/default.php";
    }

    // If headers haven't been sent yet and the redirect is for 0 seconds
    // do the redirection using HTTP headers instead of relying on a
    // browser refresh.
    if ($sec == 0 && !headers_sent()) {
        header("Location: $absolute_url");
        exit;
    }

    // If we can't do the redirection using HTTP headers (which is the common
    // case), fall back to using a <meta> tag.

    // If ampersands are present and already escaped, unescape them so
    // attr_safe() will re-escape them along with anything else
    if (strpos($absolute_url, '&amp;') !== false) {
        $absolute_url = str_replace('&amp;', '&', $absolute_url);
    }

    $meta_tag_refresh = "<meta http-equiv='refresh' content='$sec ;URL=" . attr_safe($absolute_url) . "'>";

    global $relPath;
    include_once($relPath."slim_header.inc");
    slim_header($title, ['head_data' => $meta_tag_refresh]);

    echo "$body\n";

    if ($testing) {
        echo "\n<hr>\n<i>";
        echo sprintf(_('Normally, you would be directed to the next page in %1$d seconds. However, as we are in testing mode, this has been increased to %2$d seconds. If you don\'t want to wait that long, or if you want this page inserted into your browser history, <a href="%3$s">click here</a>.'), $seconds, $sec, attr_safe($absolute_url));
        echo "</i>";
    }

    exit;
}

<?php
include_once($relPath.'misc.inc'); // startswith()

function metarefresh($seconds,$url,$title="",$body="")
// This function will redirect the browser to load a specific page. If
// possible it will use HTTP header redirection (via Location:). If that
// isn't possible, it will use a browser redirect (via a <meta> tag).
// If this is a test system ($testing=1 in site_vars.php) the redirect
// will be delayed by 15 seconds if content has already been sent to the
// client (determined if headers have been sent or if the output buffer
// has contents in it).
{
    global $testing;

    if ($testing && (headers_sent() || ob_get_length()))
    {
        $sec = $seconds + 15;
        // That may not be long enough to read everything,
        // but it should be long enough to Select All + Copy.
    }
    else
    {
        $sec = $seconds;
    }

    // If headers haven't been sent yet and the redirect is for 0 seconds
    // do the redirection using HTTP headers instead of relying on a
    // browser refresh.
    if($sec==0 && !headers_sent())
    {
        // confirm we have an absolute URL
        if(!startswith($url,'http'))
        {
            // get the proper protocol
            $absolute_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
            $absolute_url .= $_SERVER['HTTP_HOST'];
            if(!startswith($url,'/'))
            {
                $absolute_url .= dirname($_SERVER['SCRIPT_NAME']) . "/";
            }
            $absolute_url .= $url;
        }
        else
        {
            $absolute_url = $url;
        }

        header("Location: $absolute_url");
        exit;
    }

    // If we can't do the redirection using HTTP headers (which is the common
    // case), fall back to using a <meta> tag.

    // See if ambersands are present and if not escaped, escape them
    if(strpos($url, '&') !== FALSE && strpos($url, '&amp;') === FALSE) {
        $url = str_replace('&', '&amp;', $url);
    }

    $meta_tag_refresh = "<meta http-equiv=\"refresh\" content=\"$sec ;URL=$url\">";

    global $relPath;
    include_once($relPath."slim_header.inc");
    slim_header($title, array('head_data' => $meta_tag_refresh));

    echo "$body\n";

    if ($testing)
    {
    	echo "\n<hr>\n<i>";
    	echo sprintf(_('Normally, you would be directed to the next page in %1$d seconds. However, as we are in testing mode, this has been increased to %2$d seconds. If you don\'t want to wait that long, or if you want this page inserted into your browser history, <a href="%3$s">click here</a>.'), $seconds, $sec, $url);
    	echo "</i>";
    }

    exit;
}

// vim: sw=4 ts=4 expandtab

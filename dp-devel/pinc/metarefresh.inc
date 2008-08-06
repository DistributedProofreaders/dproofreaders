<?PHP
include_once($relPath.'doctype.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'misc.inc');

function metarefresh($seconds,$url,$title="",$body="")
// This function will redirect the browser to load a specific page. If
// possible it will use HTTP header redirection (via Location:). If that
// isn't possible, it will use a browser redirect (via a <meta> tag).
// If this is a test system ($testing=1 in site_vars.php) the redirect
// will be delayed by 15 seconds.
{
    global $testing, $docType, $charset;

    if ($testing)
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
            $absolute_url .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/$url";
        }
        else
        {
            $absolute_url = $url;
        }

        header("Location: $url\n\n");
        exit;
    }

    // If we can't do the redirection using HTTP headers, fall back to using
    // a <meta> tag.
    echo $docType."\n";
    echo "<html".lang_html_header()."><head><title>$title</title>\n";
    echo "<meta http-equiv=\"refresh\" content=\"$sec ;URL=$url\">\n";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\" />\n";
    echo "</head><body>\n";
    echo $body, "\n";

    if ($testing)
    {
    	echo "\n<hr>\n<i>";
    	echo sprintf(_("Normally, you would be directed to the next page in %d seconds. However, as we are in testing mode, this has been increased to %d seconds. If you don't want to wait that long, or if you want this page inserted into your browser history, <a href='%s'>click here</a>."), $seconds, $sec, $url);
    	echo "</i>";
    }

    echo "</body></html>";
    exit;
}

// vim: sw=4 ts=4 expandtab
?>

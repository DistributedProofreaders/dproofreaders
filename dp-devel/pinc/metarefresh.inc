<?PHP
include_once('doctype.inc');
include_once('site_vars.php');
include_once('gettext_setup.inc');

function metarefresh($seconds,$url,$title,$body)
{
    global $relPath, $testing, $docType, $charset;

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

<?php 
include_once("site_vars.php");   // $site_abbreviation

function slim_header($title="",$include_doctype=TRUE,$close_head_open_body=TRUE)
{
    global $charset;
    global $site_abbreviation;

    if($include_doctype)
        echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n";

    echo "<html " . lang_html_header() . ">\n";
    echo "<head>\n";
    echo "<title>$site_abbreviation";
    if (isset($title)) 
        echo ": $title";
    echo "</title>\n";
    echo "    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\" />\n";
    echo "    <meta http-equiv=\"Pragma\" content=\"no-cache\" />\n";
    echo "    <meta http-equiv=\"Expires\" content=\"-1\" />\n";

    if($close_head_open_body)
    {
        echo "</head>\n";
        echo "<body onLoad=\"self.focus()\">\n";
    }
}

function slim_footer()
{
    echo "\n</body>\n";
    echo "</html>\n";
}

// vim: sw=4 ts=4 expandtab
?>

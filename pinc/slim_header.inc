<?php 
include_once("site_vars.php");   // $site_abbreviation
include_once("languages.inc");   // lang_html_header()

function slim_header($title="",$include_doctype=TRUE,$close_head_open_body=TRUE)
{
    global $charset, $intlang;
    global $site_abbreviation;

    if($include_doctype)
        echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n";

    echo "<html " . lang_html_header($intlang) . ">\n";
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

    // Call slim_footer when the main page content has been emitted so we can
    // close the page
    register_shutdown_function('slim_footer', $close_head_open_body);
}

function slim_footer($close_body=TRUE)
{
    // Despite not needing to, some pages may still be calling slim_footer
    // so check to see if we've already output the footer.
    static $was_output = false;
    if($was_output)
        return;

    if($close_body)
        echo "\n</body>\n";
    echo "</html>\n";

    $was_output = true;
}

// vim: sw=4 ts=4 expandtab
?>

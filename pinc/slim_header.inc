<?php 
include_once($relPath."languages.inc");   // lang_html_header()

// Output valid HTML for a non-themed page. The optional $extra_args variable
// is an associative array to control what exactly is output. By default an
// HTML 4.0 transitional page header is output and the body tag is opened.
// $extra_args = array(
//     'frameset' => FALSE (default) | TRUE
//     'head_data' => strings to output as-is in <head> tag
//     'css_data' => CSS to include in the page
//     'js_data'  => JS code to include in the page,
//     'js_files' => array of .js files to link to
//     'body_attributes' => body tag attributes to output
function slim_header($title="", $extra_args=array())
{
    global $charset;
    global $site_abbreviation;

    $intlang = get_desired_language();

    if(isset($extra_args['frameset']))
    {
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" ' .
            '"http://www.w3.org/TR/html4/frameset.dtd">' . "\n";
    }
    else
    {
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">' . "\n";
    }

    echo "<html " . lang_html_header($intlang) . ">\n";
    echo "<head>\n";
    echo "<title>$site_abbreviation";
    if (isset($title)) 
        echo ": $title";
    echo "</title>\n";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\" />\n";
    echo "<meta http-equiv=\"Pragma\" content=\"no-cache\" />\n";
    echo "<meta http-equiv=\"Expires\" content=\"-1\" />\n";

    // Any additional head tags
    if(isset($extra_args['head_data']))
    {
        echo $extra_args['head_data'];
    }

    // Any additional style definitions requested by the caller
    if(isset($extra_args['css_data']))
    {
        echo "<style type='text/css'>\n" .
            $extra_args['css_data'] .
            "</style>\n";
    }

    // Per-page Javascript
    if(isset($extra_args['js_files']))
    {
        foreach($extra_args['js_files'] as $js_file)
            echo "<script language=\"JavaScript\" type=\"text/javascript\" src=\"$js_file\"></script>\n";
    }

    if(isset($extra_args['js_data']))
    {
        echo "<script type='text/javascript'>\n" .
            $extra_args['js_data'] .
            "</script>\n";
    }

    echo "</head>\n";

    // framesets don't have <body> elements
    if(!isset($extra_args['frameset']))
    {
        echo "<body";
        if(isset($extra_args['body_attributes']))
            echo " " . $extra_args['body_attributes'];
        echo ">\n";
    }

    // Call slim_footer when the main page content has been emitted so we can
    // close the page
    register_shutdown_function('slim_footer', $extra_args);
}

function slim_header_frameset($title="", $extra_args=array())
{
    $extra_args['frameset'] = TRUE;
    slim_header($title, $extra_args);
}

function slim_footer($extra_args=array())
{
    // Despite not needing to, some pages may still be calling slim_footer
    // so check to see if we've already output the footer.
    static $was_output = false;
    if($was_output)
        return;

    // framesets don't have <body> elements
    if(!isset($extra_args['frameset']))
        echo "\n</body>\n";
    echo "</html>\n";

    $was_output = true;
}

// vim: sw=4 ts=4 expandtab

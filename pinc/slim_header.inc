<?php
include_once($relPath."html_page_common.inc");

/**
 * Output valid HTML to begin a non-themed page.
 *
 * @param array $extra_args
 *   An associative array to control what exactly is output. Valid keys:
 *   - 'frameset' => FALSE (default) | TRUE
 *   - 'head_data' => strings to output as-is in <head> tag
 *   - 'css_files' => CSS files to include in the page
 *   - 'css_data' => CSS to include in the page
 *   - 'js_data'  => JS code to include in the page,
 *   - 'js_files' => array of .js files to link to
 *   - 'body_attributes' => body tag attributes to output
 */
function slim_header($title = "", $extra_args = [])
{
    global $code_url;

    // Include the slim_header stylesheet
    if (!isset($extra_args['css_files'])) {
        $extra_args['css_files'] = [];
    }
    array_unshift($extra_args['css_files'], "$code_url/styles/slim_header.css");

    output_html_header($title, $extra_args, false);

    // Call slim_footer when the main page content has been emitted so we can
    // close the page
    register_shutdown_function('slim_footer', $extra_args);
}

function slim_header_frameset($title = "", $extra_args = [])
{
    $extra_args['frameset'] = true;
    slim_header($title, $extra_args);
}

function slim_footer($extra_args = [])
{
    // Despite not needing to, some pages may still be calling slim_footer
    // so check to see if we've already output the footer.
    static $was_output = false;
    if ($was_output) {
        return;
    }

    output_html_footer($extra_args);

    $was_output = true;
}

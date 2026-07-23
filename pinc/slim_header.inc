<?php
include_once($relPath."html_page_common.inc");

/**
 * Output valid HTML to begin a non-themed page.
 * @param HeaderExtraArgs $extra_args
 */
function slim_header(string $title = "", array $extra_args = []): void
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

/** @param HeaderExtraArgs $extra_args */
function slim_header_frameset(string $title = "", array $extra_args = []): void
{
    $extra_args['frameset'] = true;
    slim_header($title, $extra_args);
}

/** @param array<string, mixed> $extra_args */
function slim_footer(array $extra_args = []): void
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

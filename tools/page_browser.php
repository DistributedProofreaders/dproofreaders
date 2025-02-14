<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'widget_text.inc');

require_login();

$title = _("Browse pages");

$js_files = [
    "$code_url/scripts/splitControl.js",
    "$code_url/scripts/image_widget.js",
    "$code_url/scripts/view_splitter_2b.js",
    "$code_url/scripts/text_widget.js",
    "$code_url/scripts/misc.js",
    "$code_url/scripts/page_browse.js",
    "$code_url/tools/page_browser.js",
    "https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js",
];

$header_args = [
    "css_files" => [
        "$code_url/styles/struct.css",
        "https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.core.css",
    ],
    "js_files" => $js_files,
    "body_attributes" => 'class="no-margin overflow-hidden fix-full"',
];

slim_header($title, $header_args);

echo "<div id='page_browser' class='column-flex' data-widget_text ='$widget_text'></div>";

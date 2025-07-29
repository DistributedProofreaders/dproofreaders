<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');

require_login();

$title = _("Browse pages");

$header_args = [
    "js_modules" => [
        "$code_url/tools/page_browser.js",
    ],
    "js_files" => [
        "$code_url/scripts/misc.js",
        "$code_url/node_modules/quill/dist/quill.js",
    ],
    "css_files" => [
        "$code_url/styles/struct.css",
        "$code_url/node_modules/quill/dist/quill.core.css",
    ],
    "body_attributes" => 'class="no-margin overflow-hidden fix-full"',
];

slim_header($title, $header_args);

echo "<div id='page_browser' class='column-flex'></div>";

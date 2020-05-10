<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()
include_once($relPath.'slim_header.inc');
include_once($relPath.'page_controls.inc');

require_login();

// get variables passed into page
$page_data = new PageData();
$mode = get_enumerated_param($_GET, 'mode', 'image', ['image', 'text', 'imageText']);

$title = _("Display Image for page");

$js_files = [
    "$code_url/scripts/splitControl.js",
    "$code_url/scripts/page_browse.js",
    "$code_url/tools/project_manager/display_image.js",
    ];

$header_args = [
    "js_files" => $js_files,
    "js_data" => $page_data->get_page_data() . get_dp_data() . "var mode = '$mode';",
    "body_attributes" => 'class="no-margin overflow-hidden"',
];

slim_header($title, $header_args);

echo "<div class='flex_100vh' id='top-div'></div>";

// vim: sw=4 ts=4 expandtab

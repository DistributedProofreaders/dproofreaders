<?php
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'page_controls.inc'); // PageData()

require_login();

$page_data = new PageData();

$js_files = [
    "$code_url/scripts/splitControl.js",
    "$code_url/scripts/page_browse.js",
    "$code_url/tools/mentors/page_text_image.js",
];

$header_args = [
    "js_files" => $js_files,
    "js_data" => $page_data->get_page_data() . get_dp_data(),
    "body_attributes" => 'class="no-margin overflow-hidden"',
];

slim_header(_("Image and text for page"), $header_args);

echo "<div class='flex_100vh' id='top-div'></div>";

// vim: sw=4 ts=4 expandtab

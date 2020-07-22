<?php
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'page_controls.inc'); // get_page_data_js()

require_login();

$error_message = "";
$projectid = "";
$imagefile = "";
try
{
    $projectid = get_projectID_param($_GET, 'project', true);
    $imagefile = get_page_image_param($_GET, 'imagefile', true);
}
catch(Exception $exception)
{
    $error_message = $exception->getMessage();
}

$round_id = get_enumerated_param($_GET, 'round_id', 'OCR', expanded_rounds());

$js_files = [
    "$code_url/scripts/splitControl.js",
    "$code_url/scripts/page_browse.js",
    "$code_url/tools/mentors/page_text_image.js",
];

$header_args = [
    "js_files" => $js_files,
    "js_data" => get_page_data_js($projectid, $imagefile, $round_id, $error_message) . get_proofreading_interface_data_js(),
    "body_attributes" => 'class="no-margin overflow-hidden"',
];

slim_header(_("Image and text for page"), $header_args);

echo "<div class='flex_100vh' id='top-div'></div>";

// vim: sw=4 ts=4 expandtab

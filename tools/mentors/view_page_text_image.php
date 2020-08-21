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
    $projectid = get_projectID_param($_GET, 'project', true) ??
        get_projectID_param($_GET, 'projectid', true);
    $imagefile = get_page_image_param($_GET, 'imagefile', true) ??
        get_page_image_param($_GET, 'page', true);
}
catch(Exception $exception)
{
    $error_message = $exception->getMessage();
}

$round_id = get_enumerated_param($_GET, 'round_id', 'OCR', expanded_rounds());
$mode = get_enumerated_param($_GET, 'mode', 'image', ['image', 'text', 'imageText']);

$js_files = [
    "$code_url/scripts/splitControl.js",
    "$code_url/scripts/page_browse.js",
    "$code_url/tools/project_manager/display_image.js",
];

$header_args = [
    "js_files" => $js_files,
    "js_data" => get_page_data_js($projectid, $imagefile, $round_id, $error_message) . get_proofreading_interface_data_js() . "
        let mode = '$mode';
        let mentorMode = true;
    ",
    "body_attributes" => 'class="no-margin overflow-hidden"',
];

if($projectid && $imagefile)
{
    $title = sprintf(_("Page %s"), $imagefile);
}
else
{
    $title = _("Image and text for page");
}

slim_header($title, $header_args);

echo "<div class='flex_100vh' id='top-div'></div>";

// vim: sw=4 ts=4 expandtab

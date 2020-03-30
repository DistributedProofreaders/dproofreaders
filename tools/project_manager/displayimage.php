<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'page_controls.inc'); // draw_size_controls()

require_login();

// get variables passed into page
$page_data = new PageData();
$page_data->validate();

$title = _("Display Image for page");

$js_files = [
    "$code_url/scripts/image_size.js",
    "$code_url/scripts/page_change.js",
    ];

$header_args = [
    "js_files" => $js_files,
    "body_attributes" => 'class="no-margin"',
];

slim_header($title, $header_args);

echo "<div class='flex_container'>
<div class='fixedbox control-form'>\n";

$page_data->draw_heading();

echo "<form method='get'>\n";
$page_data->draw_hidden_projectid();
draw_size_controls();
$page_data->draw_page_selector();
draw_text_button();
draw_image_text_button();
echo "</form>
</div>
<div class='stretchbox overflow-auto image-back'>";
$page_data->draw_image();
echo "</div>
</div>"; // flex_container

// vim: sw=4 ts=4 expandtab

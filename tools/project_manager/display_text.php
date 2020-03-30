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

$title = _("Display Text for page");

$js_files = [
    "$code_url/scripts/page_change.js",
    "$code_url/scripts/text_change.js",
    ];

$header_args = [
    "js_files" => $js_files,
    "js_data" => "
        var textUrl = '$code_url/tools/ajax_page.php?projectid=$page_data->projectid';
    ",
    "body_attributes" => 'class="no-margin"',
];

slim_header($title, $header_args);

echo "<div class='flex_container'>
<div class='fixedbox control-form'>";

$page_data->draw_heading();

echo "<form method='get'>\n";
$page_data->draw_hidden_projectid();
$page_data->draw_page_selector();
$page_data->draw_round_selector();
draw_image_button();
draw_image_text_button();
echo "</form>
</div>
<div id='text_data' class='stretchbox pre-text overflow-auto'>
</div>
</div>"; // flex_container

// vim: sw=4 ts=4 expandtab

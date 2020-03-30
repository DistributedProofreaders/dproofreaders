<?php
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'misc.inc'); // attr_safe()
include_once($relPath.'page_controls.inc'); // draw_size_controls()

require_login();

$page_data = new PageData();
$page_data->validate();

$js_files = [
    "$code_url/scripts/splitControl.js",
    "$code_url/scripts/splitter_interface.js",
    "$code_url/scripts/image_size.js",
    "$code_url/scripts/page_change.js",
    "$code_url/scripts/text_change.js",
    "$code_url/scripts/image_change.js",
    ];

$header_args = [
    "js_files" => $js_files,
    "js_data" => "
        var imageUrl = '$projects_url/$page_data->projectid/';
        var textUrl = '$code_url/tools/ajax_page.php?projectid=$page_data->projectid';
    ",
    "body_attributes" => 'class="no-margin"',
];

slim_header(_("Image and text for page"), $header_args);

echo "<div class='flex_container'>
<div class='fixedbox control-form'>\n";

$page_data->draw_heading();

echo "<form method='get'>\n";
$page_data->draw_hidden_projectid();
draw_size_controls();
$page_data->draw_page_selector();
$page_data->draw_round_selector();
draw_split_control();
draw_text_button();
draw_image_button();
echo "</form>
</div>
<div id='pane_container' class='stretchbox'>
<div class='overflow-auto image-back'>
<img id='image'></div>
<div class='dragbar'></div>
<div class='overflow-auto'>
<div id='text_data' class='pre-text'>
</div>
</div>
</div>
</div>"; // flex_container

// vim: sw=4 ts=4 expandtab

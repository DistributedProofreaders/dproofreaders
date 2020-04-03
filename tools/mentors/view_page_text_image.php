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
    "$code_url/tools/mentors/page_text_image.js",
    "$code_url/scripts/image_size.js",
    "$code_url/scripts/text_control.js",
    "$code_url/scripts/page_change.js",
    "$code_url/scripts/text_change.js",
    ];

$header_args = [
    "js_files" => $js_files,
    "body_attributes" => 'class="no-margin overflow-hidden"',
];

slim_header(_("Image and text for page"), $header_args);

echo "<div class='flex_container'>";
echo "<div class='fixedbox control-form'>";

$page_data->draw_heading();

echo "<form method='get'>\n";
$page_data->draw_hidden_projectid();
draw_size_controls();
$page_data->draw_page_selector();
$page_data->draw_round_selector();
draw_font_selector();
draw_font_size_selector();
echo "<label for='wrap'>", _("Wrap"), ":</label><input type='checkbox' id='wrap'>\n";
draw_split_control();
echo "</form>";
echo "</div>\n"; // fixedbox

echo "<div id='pane_container' class='stretchbox'>\n";

echo "<div class='overflow-auto image-back'>\n";
$page_data->draw_image();
echo "</div>\n"; // image pane
echo "<div class='dragbar'></div>";
echo "<div id='text_pane' class='pane_2'>";

echo "<div class='overflow-auto'>"; // top text pane
//The text div, we show the saved text in a textarea
echo "<textarea id='text_data' class='full-text'>";
$page_data->show_text();
echo "</textarea>\n";
echo "</div>"; // top text pane
echo "<div class='dragbar'></div>";
echo "<div class='pane_2'></div>";

echo "</div>\n"; // text_pane
echo "</div>\n"; // pane_container
echo "</div>"; // flex_container

// vim: sw=4 ts=4 expandtab

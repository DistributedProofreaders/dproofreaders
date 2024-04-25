<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'page_controls.inc'); // get_page_data_js()
include_once($relPath.'control_bar.inc'); // get_control_bar_texts()

require_login();

// (User clicked on "Start Proofreading" link or
// one of the links in "Done" or "In Progress" trays.)

$title = _("Proof test");

$js_files = [
    "$code_url/scripts/splitControl.js",
    "$code_url/scripts/control_bar.js",
    "$code_url/scripts/view_splitter.js",
    "$code_url/scripts/text_view.js",
    //    "$code_url/scripts/validate_view.js",*/
    //    "$code_url/scripts/proof_page.js",
    "$code_url/tools/proofers/proof.js",
    //    "text_operations.js",
    //    "search.js",
];

$header_args = [
    "js_files" => $js_files,
    "js_data" => get_proofreading_interface_data_js() . get_control_bar_texts(),
    "body_attributes" => 'class="no-margin overflow-hidden fix-full"',
];

slim_header($title, $header_args);

echo "<div id='page-browser' class='column-flex'></div>";

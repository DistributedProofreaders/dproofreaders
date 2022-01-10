<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'control_bar.inc'); // get_control_bar_texts()
include_once($relPath.'page_controls.inc'); // get_proofreading_interface_data_js()
include_once($relPath.'proof_texts.inc'); // get_proof_texts()

require_login();

$title = _("Proof test");

$js_files = [
    "$code_url/scripts/splitControl.js",
    "$code_url/scripts/control_bar.js",
    "$code_url/scripts/widgets.js",
    "$code_url/scripts/validator.js",
    "$code_url/tools/proofers/proof.js",
];

$header_args = [
    "js_files" => $js_files,
    "js_data" => get_proofreading_interface_data_js() . get_control_bar_texts()
    . get_proof_texts(),

    "body_attributes" => 'class="no-margin overflow-hidden fix-full"',
];

slim_header($title, $header_args);

echo "<div id='page-browser'></div>";

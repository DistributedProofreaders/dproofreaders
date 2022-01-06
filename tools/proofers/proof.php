<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');

// This is not intended to be a new proofreading interface but only to test the api.

require_login();

$title = _("Proof test");

$js_files = [
    "$code_url/scripts/splitControl.js",
    "$code_url/tools/proofers/proof.js",
];

$header_args = [
    "js_files" => $js_files,
    "body_attributes" => "style='margin: 0; overflow: hidden;'",
];

slim_header($title, $header_args);

echo "<div id='page-browser'></div>";

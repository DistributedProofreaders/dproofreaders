<?php
$relPath="../../../pinc/";
include_once($relPath.'base.inc'); // require_login()
include_once($relPath.'slim_header.inc');

// Demonstrate two pane vertical split

require_login();

$title = _('Two Pane Vertical Split');

$js_files = [
    "$code_url/scripts/splitControl.js",
    "v_split.js",
    ];

$header_args = [
    "js_files" => $js_files,
];

slim_header("$title", $header_args);

echo "<h1>$title</h1>\n";

echo "<div style='border: 10px solid black;'>\n";
echo "<div id='container' style='height: 300px;'>\n";
echo "<div>This is pane1</div>\n";
echo "<div>This is pane2</div>\n";
echo "</div>\n";
echo "</div>\n";




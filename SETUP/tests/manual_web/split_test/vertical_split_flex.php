<?php
$relPath="../../../../pinc/";
include_once($relPath.'base.inc'); // require_login()
include_once($relPath.'slim_header.inc');

// Demonstrate two pane vertical split

require_login();

$title = _('Two Pane Vertical Split with Flex Layout');

$js_files = [
    "$code_url/scripts/splitControl.js",
    "v_split.js",
];

// overflow: hidden prevents getting incorrect container size
// when using flex layout and window size is reducing
$header_args = [
    "js_files" => $js_files,
    "body_attributes" => "style='margin: 0; overflow: hidden;'",
];

slim_header("$title", $header_args);

echo "<div style='display: flex; flex-direction: column; height: 100vh;'>\n";
echo "<div style='background-color: aquamarine;'>";
echo "<h1>$title</h1>\n";
echo "</div>\n";
echo "<div id='container' style='background-color: beige; flex: auto;'>\n";
echo "<div>This is pane1</div>\n";
echo "<div>This is pane2</div>\n";
echo "</div>\n";

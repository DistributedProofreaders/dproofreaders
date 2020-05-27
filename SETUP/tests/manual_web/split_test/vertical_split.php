<?php
$relPath="../../../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');

// Demonstrate two pane vertical split

$title = _('Two Pane Vertical Split');

$js_files = [
    "$code_url/scripts/splitControl.js",
    "vertical_split.js",
    ];

$header_args = [
    "js_files" => $js_files,
    "css_files" => ["split_test.css"],
];

slim_header("$title", $header_args);

echo "<h1>$title</h1>\n";

$text = file_get_contents("sample.txt");

echo "<div style='border: 10px solid black;'>\n";
echo "<div id='container' style='height: 300px;'>\n";
echo "<div class='overflow-auto'><img src='004.png' alt='nobly sacrifice'></div>\n";
echo "<div class='overflow-auto'>$text</div>\n";
echo "</div>\n";
echo "</div>\n";




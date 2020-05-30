<?php
$relPath="../../../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');

// Demonstrate two pane vertical split

$title = _('Two Pane Vertical Split with Flex Layout');

$js_files = [
    "$code_url/scripts/splitControl.js",
    "vertical_split.js",
];

$header_args = [
    "js_files" => $js_files,
    "css_files" => ["split_test.css"],
    "body_attributes" => "style='margin: 0; overflow: hidden;'",
];

slim_header("$title", $header_args);

$text = file_get_contents("sample.txt");

echo "<div class='column-flex'>\n";
echo "<div class='top-box'>";
echo "<h1>$title</h1>\n";
echo "</div>\n";
echo "<div id='container' class='flex-auto beige'>\n";
echo "<div class='overflow-auto'><img src='004.png' alt='nobly sacrifice'></div>\n";
echo "<div class='overflow-auto'>$text</div>\n";
echo "</div>\n";

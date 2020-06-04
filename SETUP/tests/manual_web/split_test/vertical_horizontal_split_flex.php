<?php
$relPath="../../../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');

// Demonstrate two pane vertical split

$title = _('Vertical and Horizontal Split with Flex Layout');

$header_args = [
    "js_files" => [
        "$code_url/scripts/splitControl.js",
        "vertical_horizontal_split.js",
    ],
    "css_files" => ["split_test.css"],
    "body_attributes" => "style='margin: 0; overflow: hidden'",
];

$text = file_get_contents("sample.txt");

slim_header("$title", $header_args);

echo "<div class='column-flex'>\n";

echo "<div class='top-box'>";
echo "<h1>$title</h1>\n";
echo "</div>\n";

echo "<div id='top-container' class='flex-auto beige'>\n";

echo "<div class='overflow-auto'><img src='004.png' alt='nobly sacrifice'></div>\n";
echo "<div id='sub-container' style='background-color: honeydew;'>\n";
echo "<div><textarea>$text</textarea></div>\n";
echo "<div>This is pane3</div>\n";

echo "</div>\n"; // sub-container

echo "</div>\n"; // top-container

echo "</div>\n"; // flex box

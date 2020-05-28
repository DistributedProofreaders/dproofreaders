<?php
$relPath="../../../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');

// Demonstrate two pane vertical split

$title = _('Two Pane Switchable Split with Flex Layout');

$js_files = [
    "$code_url/scripts/splitControl.js",
    "switchable_split.js",
];

// overflow: hidden prevents getting incorrect container size
// when using flex layout and window size is reducing
// This happens because the height of the splitter divs is fixed so reducing
// the window height would cause a scroll bar to appear, reducing the
// client-width of the container, but we then change the height of the divs
// so the scroll bar is not drawn.
$header_args = [
    "js_files" => $js_files,
    "css_files" => ["split_test.css"],
    "body_attributes" => "style='margin: 0; overflow-y: hidden;'",
];

slim_header("$title", $header_args);

$text = file_get_contents("sample.txt");

echo "<div class='column-flex'>\n";

echo "<div class='top-box'>";
echo "<h1>$title</h1>\n";
echo "<div id='control-div'></div>";
echo "</div>\n";

echo "<div id='container' class='flex-auto beige'>\n";
echo "<div class='overflow-auto'><img src='004.png' alt='nobly sacrifice'></div>\n";
echo "<div class='overflow-auto'>$text</div>\n";
echo "</div>\n";

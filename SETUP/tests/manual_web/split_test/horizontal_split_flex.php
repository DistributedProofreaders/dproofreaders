<?php
$relPath="../../../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');

// Demonstrate two pane vertical split

$title = _('Two Pane Horizontal Split with Flex Layout');

$js_files = [
    "$code_url/scripts/splitControl.js",
    "horizontal_split.js",
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
echo "<div style='overflow: auto;'>This is pane1. They had a beautiful place of residence,
they had the most comfortable quarters, and
a superabundance of stores for their subsistence.
There they were living upon the fat of the land, without
anything under God's heaven to do. Society
was near at hand in a city populous, and furnishing
all the luxuries of life. They of course did not want
to surrender such quarters and such comforts for
the hardships and trials of a frontier station.

Finally, on June second the whole matter was laid
on the table. On May 27, 1858, the troops had been
withdrawn,143 and on July 19, 1858, the quartermaster
turned the buildings over to Mr. Steele. But
with the opening of the Civil War Fort Snelling was
used by the government as a training station, and
after the war it was continued as a permanent post.
Mr. Steele had been unable to pay the entire $90,000,
and as he claimed rent at the rate of $2000 a month
for the time it had been used by the government, the
matter was again taken up. It was finally adjusted
in an agreement whereby Mr. Steele retained the
greater part of the land, and the government kept
the buildings and 1521.20 acres surrounding the
fort. Later some of the land was re-purchased
from Mr. Steele.1
of the Department of Dakota were located within</div>\n";
echo "<div>This is pane2</div>\n";
echo "</div>\n";

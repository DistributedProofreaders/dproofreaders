<?php
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

$title = _("User Logon Statistics");
output_header($title);
echo "<h1>$title</h1>";

$images = [
    "jpgraph_files/users_logging_on.php?past=day&amp;preceding=hour",
    "jpgraph_files/users_logging_on.php?past=year&amp;preceding=hour",
    "jpgraph_files/users_logging_on.php?past=year&amp;preceding=day",
    "jpgraph_files/users_logging_on.php?past=year&amp;preceding=week",
    "jpgraph_files/users_logging_on.php?past=year&amp;preceding=fourweek",
];

foreach($images as $image)
{
    echo "<img style='max-width: 100%' src='$image'><br>\n";
}

// vim: sw=4 ts=4 expandtab

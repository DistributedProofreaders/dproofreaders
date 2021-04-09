<?php
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

$title=_("Miscellaneous User Statistics");
output_header($title);
echo "<h1>$title</h1>";

$images = [
    "jpgraph_files/average_hour_users_logging_on.php",
    "jpgraph_files/users_by_language.php",
    "jpgraph_files/users_by_country.php",
    "jpgraph_files/new_users.php?time_interval=month",
];

foreach($images as $image)
{
    echo "<img style='max-width: 100%' src='$image'><br>\n";
}


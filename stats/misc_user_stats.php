<?php
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

$title=_("Miscellaneous User Statistics");
output_header($title);
echo "<h1>$title</h1>";

echo "<img src=\"jpgraph_files/average_hour_users_logging_on.php\"><br>";
echo "<img src=\"jpgraph_files/users_by_language.php\"><br>";
echo "<img src=\"jpgraph_files/users_by_country.php\"><br>";
echo "<img src=\"jpgraph_files/new_users.php?time_interval=month\"><br>";

// vim: sw=4 ts=4 expandtab

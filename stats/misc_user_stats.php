<?php
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

$title=_("Miscellaneous User Statistics");
output_header($title);
echo "<center><h1><i>$title</i></h1></center>";


echo "<center><img src=\"jpgraph_files/average_hour_users_logging_on.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/users_by_language.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/users_by_country.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/new_users.php?time_interval=month\"></center><br>";

// vim: sw=4 ts=4 expandtab

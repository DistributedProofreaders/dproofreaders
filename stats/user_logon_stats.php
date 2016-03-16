<?php
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

output_header(_("User Logon Statistics"));
echo "<h1>" . _("User Logon Statistics") . "</h1>";

echo "<img src=\"jpgraph_files/users_logging_on.php?past=day&preceding=hour\"><br>";
echo "<img src=\"jpgraph_files/users_logging_on.php?past=year&preceding=hour\"><br>";
echo "<img src=\"jpgraph_files/users_logging_on.php?past=year&preceding=day\"><br>";
echo "<img src=\"jpgraph_files/users_logging_on.php?past=year&preceding=week\"><br>";
echo "<img src=\"jpgraph_files/users_logging_on.php?past=year&preceding=fourweek\"><br>";

// vim: sw=4 ts=4 expandtab

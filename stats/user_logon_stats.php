<?php
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

output_header(_("User Logon Statistics"));
echo "<h1>" . _("User Logon Statistics") . "</h1>";

echo "<img src=\"jpgraph_files/users_logging_on.php?past=day&amp;preceding=hour\"><br>";
echo "<img src=\"jpgraph_files/users_logging_on.php?past=year&amp;preceding=hour\"><br>";
echo "<img src=\"jpgraph_files/users_logging_on.php?past=year&amp;preceding=day\"><br>";
echo "<img src=\"jpgraph_files/users_logging_on.php?past=year&amp;preceding=week\"><br>";
echo "<img src=\"jpgraph_files/users_logging_on.php?past=year&amp;preceding=fourweek\"><br>";

// vim: sw=4 ts=4 expandtab

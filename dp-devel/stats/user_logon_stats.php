<?php
$relPath='./../pinc/';
include_once($relPath.'theme.inc');

theme(_("User Logon Statistics"),'header');
echo "<center><h1><i>"._("User Logon Statistics")."</i></h1></center>";


echo "<center><img src=\"jpgraph_files/users_logging_on.php?past=day&preceding=hour\"></center><br>";
echo "<center><img src=\"jpgraph_files/users_logging_on.php?past=year&preceding=hour\"></center><br>";
echo "<center><img src=\"jpgraph_files/users_logging_on.php?past=year&preceding=day\"></center><br>";
echo "<center><img src=\"jpgraph_files/users_logging_on.php?past=year&preceding=week\"></center><br>";
echo "<center><img src=\"jpgraph_files/users_logging_on.php?past=year&preceding=fourweek\"></center><br>";



theme('','footer');
?>

<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'project_states.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();

theme(_("User Logon Statistics"),'header');
echo "<center><h1><i>"._("User Logon Statistics")."</i></h1></center>";


echo "<center><img src=\"jpgraph_files/last24_hour_users_logging_on.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/hour_users_logging_on.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/day_users_logging_on.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/week_users_logging_on.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/fourweek_users_logging_on.php\"></center><br>";



theme('','footer');
?>

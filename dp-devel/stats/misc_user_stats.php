<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'project_states.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();

$title=_("Miscellaneous User Statistics");
theme($title,'header');
echo "<center><h1><i>$title</i></h1></center>";


echo "<center><img src=\"jpgraph_files/average_hour_users_logging_on.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/users_by_language.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/users_by_country.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/users_by_month_joined.php\"></center><br>";

theme('','footer');
?>

<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'http_headers.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once('../includes/team.php');
$db_Connection=new dbConnect();

$result = select_from_teams("id = {$_GET['tid']}");
$curTeam = mysql_fetch_assoc($result);

$stats = _("Statistics");

theme($curTeam['teamname']." ".$stats, "header");
echo "<br><center>";

showTeamInformation($curTeam);

echo "</center>";
theme("", "footer");
?>

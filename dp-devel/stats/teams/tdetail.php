<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once('../includes/team.php');
$db_Connection=new dbConnect();

$result = mysql_query("SELECT * FROM user_teams WHERE id = ".$_GET['tid']);
$curTeam = mysql_fetch_assoc($result);

theme($curTeam['teamname']." Stats", "header");
echo "<br><center>";
showTeamProfile($curTeam);
if ($_GET['tid'] != 1) {
	showTeamStats($curTeam);
	showTeamMbrs($curTeam);
	showTeamHistory($curTeam);
}
echo "</center>";
theme("", "footer");
?>
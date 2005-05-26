<?
$relPath="./../../pinc/";
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'http_headers.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once('../includes/team.inc');
$db_Connection=new dbConnect();

$tally_name = array_get( $_GET, 'tally_name', null );

$result = select_from_teams("id = {$_GET['tid']}");
$curTeam = mysql_fetch_assoc($result);

$stats = _("Statistics");

theme($curTeam['teamname']." ".$stats, "header");
echo "<br><center>";

showTeamInformation($curTeam, $tally_name);

echo "</center>";
theme("", "footer");
?>

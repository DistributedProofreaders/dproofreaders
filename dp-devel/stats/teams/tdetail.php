<?
$relPath="./../../pinc/";
include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'http_headers.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once('../includes/team.inc');
$db_Connection=new dbConnect();

$tally_name = array_get( $_GET, 'tally_name', null );

$req_team_id = get_integer_param( $_GET, 'tid', null, 0, null );

$result = select_from_teams("id = {$req_team_id}");
$curTeam = mysql_fetch_assoc($result);

$stats = _("Statistics");

theme($curTeam['teamname']." ".$stats, "header");
echo "<br><center>";

showTeamInformation($curTeam, $tally_name);

echo "</center>";
theme("", "footer");
?>

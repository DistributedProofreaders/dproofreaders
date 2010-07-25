<?php
$relPath="./../../pinc/";
include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'http_headers.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once('../includes/team.inc');
$db_Connection=new dbConnect();


# tally_name may be empty/unspecified, or a round name.
$valid_tally_names = array_keys($page_tally_names);
$tally_name   = get_enumerated_param($_GET, 'tally_name', null, $valid_tally_names, true);
$req_team_id  = get_integer_param( $_GET, 'tid', null, 0, null );

$result = select_from_teams("id = {$req_team_id}");
$curTeam = mysql_fetch_assoc($result);

// TRANSLATORS: %s is a team name
$title = sprintf(_("%s Statistics"), $curTeam['teamname']);
theme($title, "header");
echo "<br><center>";

showTeamInformation($curTeam, $tally_name);

echo "</center>";
theme("", "footer");
?>

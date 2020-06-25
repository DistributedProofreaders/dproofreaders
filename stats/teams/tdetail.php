<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'http_headers.inc');
include_once($relPath.'theme.inc');
include_once('../includes/team.inc'); // showTeamInformation()

require_login();

# tally_name may be empty/unspecified, or a round name.
$valid_tally_names = array_keys(get_page_tally_names());
$tally_name   = get_enumerated_param($_GET, 'tally_name', null, $valid_tally_names, true);
$req_team_id  = get_integer_param( $_GET, 'tid', null, 0, null );

$result = select_from_teams("id = {$req_team_id}");
$curTeam = mysqli_fetch_assoc($result);

if(!$curTeam)
    die(sprintf("%d is an invalid team ID", $req_team_id));

// TRANSLATORS: %s is a team name
$title = sprintf(_("%s Statistics"), $curTeam['teamname']);
output_header($title);
echo "<br><div class='center-align'>";

showTeamInformation($curTeam, $tally_name);

echo "</div>";

// vim: sw=4 ts=4 expandtab

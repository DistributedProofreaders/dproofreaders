<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'misc.inc'); // xmlencode()
include_once($relPath.'page_tally.inc');
include_once($relPath.'forum_interface.inc'); // get_url_to_view_topic
include_once($relPath.'misc.inc'); // get_integer_param()
include_once('../includes/team.inc');
include_once('../includes/member.inc');

require_login();

if (empty($_GET["id"])) {
    include_once($relPath.'theme.inc');
    output_header(_("Error!"));
    echo "<p class='error'>";
    echo sprintf(_("A team id must specified in the following format: %s"), "$code_url/stats/teams/teams_xml.php?id=*****");
    echo "</p>";
    exit();
}

$req_team_id = get_integer_param( $_GET, 'id', null, 0, null );

//Try our best to make sure no browser caches the page
header("Content-Type: text/xml; charset=$charset");
header("Expires: Sat, 1 Jan 2000 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$result = select_from_teams("id = {$req_team_id}");
$curTeam = mysqli_fetch_assoc($result);

$team_id = $curTeam['id'];

//Team info portion of $data

$result = mysqli_query(DPDatabase::get_connection(), "SELECT COUNT(id) AS totalTeams FROM user_teams");
$row = mysqli_fetch_assoc($result);
$totalTeams = $row["totalTeams"];

$data = "<teaminfo id='$team_id'>
        <teamname>".xmlencode($curTeam['teamname'])."</teamname>
        <datecreated>".date("m/d/Y", $curTeam['created'])."</datecreated>
        <createdby>".xmlencode($curTeam['createdby'])."</createdby>
        <leader>".xmlencode(get_username_for_uid($curTeam['owner']))."</leader>
        <description>".xmlencode($curTeam['team_info'])."</description>
        <website>".xmlencode($curTeam['webpage'])."</website>
        <forums>".xmlencode(get_url_to_view_topic($curTeam['topic_id']))."</forums>
        <totalmembers>".$curTeam['member_count']."</totalmembers>
        <currentmembers>".Team::active_member_count($team_id)."</currentmembers>
        <retiredmembers>".($curTeam['member_count'] - Team::active_member_count($team_id))."</retiredmembers>";

foreach ( get_page_tally_names() as $tally_name => $tally_title )
{
    $teams_tallyboard = new TallyBoard( $tally_name, 'T' );

    $pageCount = $teams_tallyboard->get_current_tally( $team_id );
    $pageCountRank = $teams_tallyboard->get_rank( $team_id );

    $avg_pages_per_day = get_daily_average( $curTeam['created'], $pageCount );

    list($bestDayCount, $bestDayTimestamp) =
        $teams_tallyboard->get_info_re_largest_delta( $team_id );
    $bestDayTime = date("M. jS, Y", ($bestDayTimestamp-1));

    $data .= "
        <roundinfo id='$tally_name'>
            <totalpages>$pageCount</totalpages>
            <rank>".$pageCountRank."/".$totalTeams."</rank>
            <avgpagesday>".number_format($avg_pages_per_day,1)."</avgpagesday>
            <mostpagesday>".$bestDayCount." (".$bestDayTime.")</mostpagesday>
        </roundinfo>";
}

$data .= "
    </teaminfo>
";

//Team members portion of $data
$data .= "<teammembers>";
$sql = sprintf("
    SELECT username, date_created, u_id, u_privacy
    FROM users
    WHERE u_id IN (
        SELECT u_id
        FROM user_teams_membership
        WHERE t_id = %d
    )
    ORDER BY username ASC
", $team_id);
$mbrQuery = DPDatabase::query($sql);
while ($curMbr = mysqli_fetch_assoc($mbrQuery))
{
    if ($curMbr['u_privacy'] == PRIVACY_PRIVATE)
    {
        $data .= "<member id=\"".$curMbr['u_id']."\">
            <username>".xmlencode($curMbr['username'])."</username>
            <datejoined>".date("m/d/Y", $curMbr['date_created'])."</datejoined>
        </member>
        ";
    }
}
$data .= "</teammembers>";


$xmlpage = "<"."?"."xml version=\"1.0\" encoding=\"$charset\" ?".">
<teamstats xmlns:xsi=\"http://www.w3.org/2000/10/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"teamstats.xsd\">
$data
</teamstats>";

echo $xmlpage;

// vim: sw=4 ts=4 expandtab

<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'xml.inc');
include_once($relPath.'page_tally.php');
include_once('../includes/team.php');
include_once('../includes/member.php');
$db_Connection=new dbConnect();

if (empty($_GET['id']) || $_GET['id'] == 1) {
	include_once($relPath.'theme.inc');
	theme("Error!", "header");
	if (empty($_GET['id'])) {
		echo "<br><center>A team id must specified in the following format:<br>$code_url/stats/teams/teams_xml.php?id=****</center>";
	} else {
		echo "<br><center>XML Statistics are not gathered for the default Distributed Proofreaders team.  Please choose a different team.</center>";
	}
	theme("", "footer");
	exit();
}

//Try our best to make sure no browser caches the page
header("Content-Type: text/xml");
header("Expires: Sat, 1 Jan 2000 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$result = select_from_teams("id = {$_GET['id']}");
$curTeam = mysql_fetch_assoc($result);

//Team info portion of $data
	$pageCountRank = $teams_P_page_tallyboard->get_rank( $curTeam['id'] );

	$result = mysql_query("SELECT COUNT(id) AS totalTeams FROM user_teams");
	$totalTeams = (mysql_result($result, 0, "totalTeams") - 1);

	$avg_pages_per_day = get_daily_average( $curTeam['created'], $curTeam['page_count'] );

	list($bestDayCount, $bestDayTimestamp) =
		$teams_P_page_tallyboard->get_info_re_largest_delta( $curTeam['id'] );
	$bestDayTime = date("M. jS, Y", ($bestDayTimestamp-86400));

	$data = "<teaminfo id=\"".$curTeam['id']."\">
			<teamname>".xmlencode($curTeam['teamname'])."</teamname>
			<datecreated>".date("m/d/Y", $curTeam['created'])."</datecreated>
			<leader>".xmlencode($curTeam['createdby'])."</leader>
			<totalpages>".$curTeam['page_count']."</totalpages>
			<description>".xmlencode($curTeam['team_info'])."</description>
			<website>".xmlencode($curTeam['webpage'])."</website>
			<forums>".xmlencode($GLOBALS['forums_url']."/viewtopic.php?t=".$curTeam['topic_id'])."</forums>
			<totalmembers>".$curTeam['member_count']."</totalmembers>
			<currentmembers>".$curTeam['active_members']."</currentmembers>
			<retiredmembers>".($curTeam['member_count'] - $curTeam['active_members'])."</retiredmembers>
			<rank>".$pageCountRank."/".$totalTeams."</rank>
			<avgpagesday>".number_format($avg_pages_per_day,1)."</avgpagesday>
			<mostpagesday>".$bestDayCount." (".$bestDayTime.")</mostpagesday>
		</teaminfo>
	";

//Team members portion of $data
	$data .= "<teammembers>";
	$mbrQuery = mysql_query("
		SELECT username, date_created, u_id, u_privacy,
			$user_P_page_tally_column AS current_P_page_tally
		FROM users $joined_with_user_P_page_tallies
		WHERE {$curTeam['id']} IN (team_1, team_2, team_3)
		ORDER BY username ASC
	");
	while ($curMbr = mysql_fetch_assoc($mbrQuery))
	{
		if ($curMbr['u_privacy'] == PRIVACY_PUBLIC)
		{
			$data .= "<member id=\"".$curMbr['u_id']."\">
				<username>".xmlencode($curMbr['username'])."</username>
				<pagescompleted>".$curMbr['current_P_page_tally']."</pagescompleted>
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
?>

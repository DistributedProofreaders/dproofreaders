<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'connect.inc');
include_once($relPath.'xml.inc');
include_once($relPath.'page_tally.php');
include_once('../includes/team.php');
include_once('../includes/member.php');
$db_Connection=new dbConnect();

if (empty($_GET['username'])) {
	include_once($relPath.'theme.inc');
	theme("Error!", "header");
	echo "<br><center>A username must specified in the following format:<br>$code_url/stats/members/mbr_xml.php?username=*****</center>";
	theme("", "footer");
	exit();
}

//Try our best to make sure no browser caches the page
header("Content-Type: text/xml");
header("Expires: Sat, 1 Jan 2000 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

echo "<?xml version=\"1.0\" encoding=\"$charset\" ?>\n";
echo "<memberstats xmlns:xsi=\"http://www.w3.org/2000/10/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"memberstats.xsd\">\n";
	

$result = mysql_query("
	SELECT *
	FROM users
	WHERE username = '".$_GET['username']."'
	LIMIT 1
");
$curMbr = mysql_fetch_assoc($result);
$result = mysql_query("SELECT * FROM phpbb_users WHERE username = '".$curMbr['username']."'");
$curMbr = array_merge($curMbr, mysql_fetch_assoc($result));

$current_page_tally = $users_P_page_tallyboard->get_current_tally( $curMbr['u_id'] );
$currentRank = $users_P_page_tallyboard->get_rank( $curMbr['u_id'] );

list($bestDayCount,$bestDayTimestamp) =
	$users_P_page_tallyboard->get_info_re_largest_delta($curMbr['u_id']);
$bestDayTime = date("M. jS, Y", ($bestDayTimestamp-86400));

$now = time();
$daysInExistence = floor(($now - $curMbr['date_created'])/86400);
if ($daysInExistence > 0) {
	        $daily_Average = $current_page_tally/$daysInExistence;
} else {
		$daily_Average = 0;
}


//User info
if ($curMbr['u_privacy'] == PRIVACY_PUBLIC)
{
	echo "
		<userinfo id=\"".$curMbr['u_id']."\">
			<username>".xmlencode($curMbr['username'])."</username>
			<datejoined>".date("m/d/Y", $curMbr['date_created'])."</datejoined>
			<lastlogin>".date("m/d/Y", $curMbr['last_login'])."</lastlogin>
			<pagescompleted>$current_page_tally</pagescompleted>
			<overallrank>$currentRank</overallrank>
			<bestdayever>
				<pages>$bestDayCount</pages>
				<date>$bestDayTime</date>
			</bestdayever>
			<dailyaverage>".number_format($daily_Average)."</dailyaverage>
			<location>".xmlencode($curMbr['user_from'])."</location>
			<occupation>".xmlencode($curMbr['user_occ'])."</occupation>
			<interests>".xmlencode($curMbr['user_interests'])."</interests>
			<website>".xmlencode($curMbr['user_website'])."</website>
		</userinfo>";

//Team info
	$result = select_from_teams("id IN ({$curMbr['team_1']}, {$curMbr['team_2']}, {$curMbr['team_3']})");
	echo "
		<teaminfo>";
	while ($row = mysql_fetch_assoc($result)) {
		echo "
			<team>
			<name>".xmlencode($row['teamname'])."</name>
			<pagescompleted>".$row['page_count']."</pagescompleted>
			<activemembers>".$row['active_members']."</activemembers>
			</team>";
	}
	echo "
		</teaminfo>";


//Neighbor info

	$neighbors =
		user_get_page_tally_neighborhood(
			'P', $curMbr['username'], 4 );

	echo "
		<neighborinfo>";
	foreach ( $neighbors as $rel_posn => $neighbor )
	{
		echo "
			<neighbor>
				<rank>".$neighbor->get_current_page_tally_rank()."</rank>";

		if (!$neighbor->is_anonymized())
		{
			echo "
				<username>".xmlencode($neighbor->get_username())."</username>
				<datejoined>".date("m/d/Y", $neighbor->get_date_joined())."</datejoined>";
		}

		echo "
				<pagescompleted>".$neighbor->get_current_page_tally()."</pagescompleted>
			</neighbor>";
	}
	echo "
		</neighborinfo>";
}

echo "
</memberstats>";

?>

<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'connect.inc');
include_once($relPath.'xml.inc');
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

$result = mysql_query("SELECT * FROM users WHERE username = '".$_GET['username']."' LIMIT 1");
$curMbr = mysql_fetch_assoc($result);
$result = mysql_query("SELECT * FROM phpbb_users WHERE username = '".$curMbr['username']."'");
$curMbr = array_merge($curMbr, mysql_fetch_assoc($result));

$rankArray = mbrRank($curMbr['username']);
$bestDay = bestDayEver($curMbr['u_id']);

$now = time();
$daysInExistence = floor(($now - $curMbr['date_created'])/86400);
if ($daysInExistence > 0) {
	        $daily_Average = $curMbr['pagescompleted']/$daysInExistence;
} else {
		$daily_Average = 0;
}

	

$data = '';

//User info portion of $data
if ($curMbr['u_privacy'] == PRIVACY_PUBLIC)
{
	$data = "<userinfo id=\"".$curMbr['u_id']."\">
			<username>".xmlencode($curMbr['username'])."</username>
			<datejoined>".date("m/d/Y", $curMbr['date_created'])."</datejoined>
			<lastlogin>".date("m/d/Y", $curMbr['last_login'])."</lastlogin>
			<pagescompleted>".$curMbr['pagescompleted']."</pagescompleted>
			<overallrank>".$rankArray['curMbrRank']."</overallrank>
			<bestdayever>
				<pages>".$bestDay['count']."</pages>
				<date>".$bestDay['time']."</date>
			</bestdayever>
			<dailyaverage>".number_format($daily_Average)."</dailyaverage>
			<location>".xmlencode($curMbr['user_from'])."</location>
			<occupation>".xmlencode($curMbr['user_occ'])."</occupation>
			<interests>".xmlencode($curMbr['user_interests'])."</interests>
			<website>".xmlencode($curMbr['user_website'])."</website>
		</userinfo>
	";

//Team info portion of $data
	$result = mysql_query("SELECT id, teamname, active_members, page_count FROM user_teams WHERE id = ".$curMbr['team_1']." || id = ".$curMbr['team_2']." || id = ".$curMbr['team_3']."");
	$data .= "<teaminfo>";
	while ($row = mysql_fetch_assoc($result)) {
		$data .= "<team>
		<name>".xmlencode($row['teamname'])."</name>
		<pagescompleted>".$row['page_count']."</pagescompleted>
		<activemembers>".$row['active_members']."</activemembers>
		</team>
		";
	}
	$data .= "</teaminfo>";


//Neighbor info portion of $data
	$curMbr_i = $rankArray['curMbrIndex'];
	$data .= "<neighborinfo>";
	$i = 4;
	if ($rankArray['rank'][$curMbr_i] <= 4) { $i = $rankArray['rank'][$curMbr_i]-1; }
	while (!empty($i)) {
		$j = $curMbr_i-$i;
		$result = mysql_query("SELECT date_created FROM users WHERE username = '".$rankArray['username'][$j]."'");

		$data .= "<neighbor>
			<rank>".$rankArray['rank'][$j]."</rank>
			<username>".xmlencode($rankArray['username'][$j])."</username>
			<datejoined>".date("m/d/Y", @mysql_result($result, 0, "date_created"))."</datejoined>
			<pagescompleted>".$rankArray['pages'][$j]."</pagescompleted>
		</neighbor>
		";
		$i--;
	}

	$data .= "<neighbor>
			<rank>".$rankArray['rank'][$curMbr_i]."</rank>
			<username>".xmlencode($curMbr['username'])."</username>
			<datejoined>".date("m/d/Y", $curMbr['date_created'])."</datejoined>
			<pagescompleted>".$curMbr['pagescompleted']."</pagescompleted>
		</neighbor>
		";

	$i = 1;
	while ($i <= 4) {
		$j = $curMbr_i+$i;
		if (empty($rankArray['rank'][$j])) { break; }
		$result = mysql_query("SELECT u_id, date_created FROM users WHERE username = '".$rankArray['username'][$j]."'");

		$data .= "<neighbor>
			<rank>".$rankArray['rank'][$j]."</rank>
			<username>".xmlencode($rankArray['username'][$j])."</username>
			<datejoined>".date("m/d/Y", mysql_result($result, 0, "date_created"))."</datejoined>
			<pagescompleted>".$rankArray['pages'][$j]."</pagescompleted>
		</neighbor>
		";
		$i++;
	}


	$data .= "</neighborinfo>
	";
}
else
{
	$data = '';
}

$xmlpage = "<"."?"."xml version=\"1.0\" encoding=\"$charset\" ?".">
<memberstats xmlns:xsi=\"http://www.w3.org/2000/10/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"memberstats.xsd\">
$data
</memberstats>";

echo $xmlpage;
?>

<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once('../includes/team.php');
include_once('../includes/member.php');
$db_Connection=new dbConnect();

function xmlencode($data) {
	$trans_array = array();
	for ($i=127; $i<255; $i++) {
		$trans_array[chr($i)] = "&#" . $i . ";";
		}
      	$data = strtr($data, $trans_array);
	$data = htmlentities($data, ENT_QUOTES);
       	return $data;
}

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

//User info portion of $data
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
			<dailyaverage>".number_format(dailyAverage($curMbr['u_id']))."</dailyaverage>
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
	$data .= "<neighborinfo>";
	$i = 4;
	if ($rankArray['rank'][$rankArray['curMbrIndex']] <= 4) { $i = $rankArray['rank'][$rankArray['curMbrIndex']]-1; }
	while (!empty($i)) {
		$result = mysql_query("SELECT date_created FROM users WHERE username = '".$rankArray['username'][$rankArray['curMbrIndex']-$i]."'");

		$data .= "<neighbor>
			<rank>".$rankArray['rank'][$rankArray['curMbrIndex']-$i]."</rank>
			<username>".xmlencode($rankArray['username'][$rankArray['curMbrIndex']-$i])."</username>
			<datejoined>".date("m/d/Y", mysql_result($result, 0, "date_created"))."</datejoined>
			<pagescompleted>".$rankArray['pages'][$rankArray['curMbrIndex']-$i]."</pagescompleted>
		</neighbor>
		";
		$i--;
	}

	$data .= "<neighbor>
			<rank>".$rankArray['rank'][$rankArray['curMbrIndex']]."</rank>
			<username>".xmlencode($curMbr['username'])."</username>
			<datejoined>".date("m/d/Y", $curMbr['date_created'])."</datejoined>
			<pagescompleted>".$curMbr['pagescompleted']."</pagescompleted>
		</neighbor>
		";

	$i = 1;
	while ($i <= 4) {
		$result = mysql_query("SELECT u_id, date_created FROM users WHERE username = '".$rankArray['username'][$rankArray['curMbrIndex']+$i]."'");

		$data .= "<neighbor>
			<rank>".$rankArray['rank'][$rankArray['curMbrIndex']+$i]."</rank>
			<username>".xmlencode($rankArray['username'][$rankArray['curMbrIndex']+$i])."</username>
			<datejoined>".date("m/d/Y", mysql_result($result, 0, "date_created"))."</datejoined>
			<pagescompleted>".$rankArray['pages'][$rankArray['curMbrIndex']+$i]."</pagescompleted>
		</neighbor>
		";
		$i++;
	}


	$data .= "</neighborinfo>
	";

$xmlpage = "<"."?"."xml version=\"1.0\" encoding=\"ISO-8859-1\" ?".">
<memberstats xmlns:xsi=\"http://www.w3.org/2000/10/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"memberstats.xsd\">
$data
</memberstats>";

echo $xmlpage;
?>
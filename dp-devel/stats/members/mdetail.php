<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once('../includes/team.php');
include_once('../includes/member.php');
$db_Connection=new dbConnect();

$result = mysql_query("SELECT * FROM users WHERE u_id = ".$_GET['id']."");
$curMbr = mysql_fetch_assoc($result);
$result = mysql_query("SELECT * FROM phpbb_users WHERE username = '".$curMbr['username']."'");
$curMbr = array_merge($curMbr, mysql_fetch_assoc($result));
$now = time();

theme($curMbr['username']."'s Statistics", "header");
echo "<br><center>";
if (!empty($curMbr['u_id'])) {
	showMbrProfile($curMbr);
	if (!empty($curMbr['team_1']) || !empty($curMbr['team_2']) || !empty($curMbr['team_3'])) { showMbrTeams($curMbr); }
	if ($curMbr['pagescompleted'] > 0) { showMbrNeighbors($curMbr); }
	if (($now - $curMbr['date_created']) > 86400) { showMbrHistory($curMbr); }
}

echo "</center>";
theme("", "footer");
?>
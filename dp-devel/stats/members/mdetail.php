<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
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

if ($curMbr['u_privacy'] != PRIVACY_ANONYMOUS || $curMbr['username'] == $pguser) {
	$isAnonymousUsername = $curMbr['username'];
	if (substr($curMbr['username'], -1) != "s") { $needsApostrophe = "s"; } else { $needsApostrophe = ""; }
} else {
	$isAnonymousUsername = _("Anonymous");
	$needsApostrophe = "";
}

$desc = "$isAnonymousUsername'$needsApostrophe "._("Statistics");
theme($desc, "header");

echo "<br><center>";
if (!empty($curMbr['u_id'])) {
	if ($isAnonymousUsername == _("Anonymous") && $curMbr['username'] != $pguser) {
		echo "<p>"._("This user has requested to remain anonymous.")."</p>";
	} elseif ($curMbr['u_privacy'] == PRIVACY_PRIVATE) {
		if (!isset($pguser)) {
			echo "<p>"._("This user has requested their statistics remain private.  Please create an account to view their statistics.")."</p>";
		} else {
			showMbrProfile($curMbr);
			if (!empty($curMbr['team_1']) || !empty($curMbr['team_2']) || !empty($curMbr['team_3'])) { showMbrTeams($curMbr); }
			if ($curMbr['pagescompleted'] > 0) { showMbrNeighbors($curMbr); }
			if (($now - $curMbr['date_created']) > 86400) { showMbrHistory($curMbr); }
		}
	} else {
		showMbrProfile($curMbr);
		if (!empty($curMbr['team_1']) || !empty($curMbr['team_2']) || !empty($curMbr['team_3'])) { showMbrTeams($curMbr); }
		if ($curMbr['pagescompleted'] > 0) { showMbrNeighbors($curMbr); }
		if (($now - $curMbr['date_created']) > 86400) { showMbrHistory($curMbr); }
	}
}

echo "</center>";
theme("", "footer");
?>

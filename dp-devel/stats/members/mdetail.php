<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once('../includes/team.php');
include_once('../includes/member.php');
$db_Connection=new dbConnect();

$id = array_get( $_GET, 'id', '' );
if (empty($id)) {
	echo "mdetail.php: missing or empty 'id' parameter";
	exit;
}

$result = mysql_query("SELECT * FROM users WHERE u_id = '$id'");

if (mysql_num_rows($result) == 0)
{
	echo "mdetail.php: no user with u_id='$id'";
	exit;
}

$curMbr = mysql_fetch_assoc($result);
$result = mysql_query("SELECT * FROM phpbb_users WHERE username = '".$curMbr['username']."'");
$curMbr = array_merge($curMbr, mysql_fetch_assoc($result));
$now = time();

if ($curMbr['u_privacy'] != PRIVACY_ANONYMOUS || $curMbr['username'] == $pguser) {
	$isAnonymousUsername = $curMbr['username'];
} else {
	$isAnonymousUsername = _("Anonymous");
}

$desc = sprintf( _("Details for user '%s'"), $isAnonymousUsername );
theme($desc, "header");

echo "<br><center>";

echo "<h1>$desc</h1>";

	if ($curMbr['u_privacy'] == PRIVACY_ANONYMOUS && $curMbr['username'] != $pguser) {
		echo "<p>"._("This user has requested to remain anonymous.")."</p>";
	} elseif ($curMbr['u_privacy'] == PRIVACY_PRIVATE && !isset($pguser)) {
			echo "<p>"._("This user has requested their statistics remain private.")."</p>";
	} else {
		showMbrProfile($curMbr);
		if (!empty($curMbr['team_1']) || !empty($curMbr['team_2']) || !empty($curMbr['team_3'])) { showMbrTeams($curMbr); }
		if ($curMbr['pagescompleted'] > 0) { showMbrNeighbors($curMbr); }
		if (($now - $curMbr['date_created']) > 86400) { showMbrHistory($curMbr); }
	}

echo "</center>";
theme("", "footer");
?>

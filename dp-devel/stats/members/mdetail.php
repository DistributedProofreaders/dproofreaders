<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'page_tally.php');
include_once('../includes/team.php');
include_once('../includes/member.php');
$db_Connection=new dbConnect();

$id = array_get( $_GET, 'id', '' );
if (empty($id)) {
	echo "mdetail.php: missing or empty 'id' parameter";
	exit;
}

$result = mysql_query("
	SELECT *
	FROM $users_table_with_tallies
	WHERE u_id = '$id'
");

if (mysql_num_rows($result) == 0)
{
	echo "mdetail.php: no user with u_id='$id'";
	exit;
}

$curMbr = mysql_fetch_assoc($result);

if ($curMbr['u_privacy'] == PRIVACY_ANONYMOUS && $curMbr['username'] != $pguser) {
	$user_referent = "#" . $curMbr['u_id'];
	// Note that this doesn't reveal anything;
	// the requestor already knows the subject's u_id,
	// because it was included in the request.
	$brushoff = _("This user has requested to remain anonymous.");
} elseif ($curMbr['u_privacy'] == PRIVACY_PRIVATE && !isset($pguser)) {
	$user_referent = "#" . $curMbr['u_id'];
	$brushoff = _("This user has requested their statistics remain private.");
} else {
	$user_referent = "'" . $curMbr['username'] . "'";
	$brushoff = NULL;
}

$desc = sprintf( _("Details for user %s"), $user_referent );
theme($desc, "header");

echo "<br><center>";

echo "<h1>$desc</h1>";

if (is_null($brushoff)) {
	$result = mysql_query("SELECT * FROM phpbb_users WHERE username = '".$curMbr['username']."'");
	$curMbr = array_merge($curMbr, mysql_fetch_assoc($result));
	$now = time();
	showMbrProfile($curMbr);
	if (!empty($curMbr['team_1']) || !empty($curMbr['team_2']) || !empty($curMbr['team_3'])) { showMbrTeams($curMbr); }
	if ($curMbr[$user_P_page_tally_column] > 0) { showMbrNeighbors($curMbr); }
	if (($now - $curMbr['date_created']) > 86400) { showMbrHistory($curMbr); }
} else {
	echo "<p>$brushoff</p>";
}

echo "</center>";
theme("", "footer");
?>

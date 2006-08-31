<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'privacy.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'page_tally.inc');
include_once('../includes/team.inc');
include_once('../includes/member.inc');
$db_Connection=new dbConnect();

$tally_name = array_get( $_GET, 'tally_name', null );

$id = array_get( $_GET, 'id', '' );
if (empty($id)) {
	echo "mdetail.php: missing or empty 'id' parameter";
	exit;
}

$result = mysql_query("
	SELECT *
	FROM users
	WHERE u_id = '$id'
");

if (mysql_num_rows($result) == 0)
{
	echo "mdetail.php: no user with u_id='$id'";
	exit;
}

$curMbr = mysql_fetch_assoc($result);

$can_reveal = can_reveal_details_about( $curMbr['username'], $curMbr['u_privacy'] );
if ( $can_reveal )
{
	$user_referent = "'" . $curMbr['username'] . "'";
}
else
{
	$user_referent = "#" . $curMbr['u_id'];
	// Note that this doesn't reveal anything;
	// the requestor already knows the subject's u_id,
	// because it was included in the request.
}

$desc = sprintf( _("Details for user %s"), $user_referent );
theme($desc, "header");

echo "<br><center>";

echo "<h1>$desc</h1>";

if ( $can_reveal )
{
	if ( $curMbr['u_privacy'] == PRIVACY_ANONYMOUS )
	{
		$visibility_note = _("These stats are visible to Site Admins and the user only.");
		echo "<i>($visibility_note)</i><br>\n";
	}
	showMbrInformation( $curMbr, $tally_name );
}
else
{
	$brushoff = _("This user has requested that their statistics remain private.");
	echo "<p>$brushoff</p>";
}

echo "</center>";
theme("", "footer");
?>

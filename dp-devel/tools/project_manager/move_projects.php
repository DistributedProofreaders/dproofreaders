<?
$relPath='../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'project_trans.inc');
include_once('projectmgr.inc');
new dbConnect();

$curr_state = $_GET['curr_state'];
$new_state  = $_GET['new_state'];
$projectids = explode( ',', $_GET['projects'] );

abort_if_not_manager();

echo "<pre>\n";

echo "Moving projects from '$curr_state' to '$new_state'...\n";
echo "\n";

foreach( $projectids as $projectid )
{
	echo "\n";
	echo "$projectid ...\n";

	$result = user_can_edit_project($projectid);
	if ( $result == PROJECT_DOES_NOT_EXIST )
	{
		echo "    does not exist.\n";
		continue;
	}
	else if ( $result == USER_CANNOT_EDIT_PROJECT )
	{
		echo "    You are not allowed to edit that project.\n";
		continue;
	}

	$res = mysql_query("
		SELECT state, nameofwork
		FROM projects
		WHERE projectid='$projectid'
	") or die(mysql_error());

	$project = mysql_fetch_assoc( $res );

	echo "    {$project['nameofwork']}\n";

	if ( $project['state'] != $curr_state )
	{
		echo "    is no longer in $curr_state (now in {$project['state']}).\n";
		continue;
	}

	$error_msg = project_transition( $projectid, $new_state );
	if ( $error_msg )
	{
		echo "    $error_msg\n";
		continue;
	}

	echo "    successfully moved\n";
}

echo "</pre>\n";

echo "<hr>\n";
echo "<p>Back to <a href='projectmgr.php'>project manager</a> page.</p>\n";
?>

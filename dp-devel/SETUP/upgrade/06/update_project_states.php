<?PHP
$relPath = './pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'project_states.inc');
new dbConnect();

// The project states denoted by:
//     PROJ_SUBMIT_PG_UNAVAILABLE
//     PROJ_SUBMIT_PG_AVAILABLE
//     PROJ_SUBMIT_PG_POSTING
// have been discontinued.
// Find any projects in those states and move them to PROJ_POST_COMPLETE.

echo "<pre>\n";

$changes = array(
	array('proj_submit_pgunavailable',PROJ_POST_COMPLETE),
	array('proj_submit_pgavailable',  PROJ_POST_COMPLETE),
	array('proj_submit_pgposting',    PROJ_POST_COMPLETE),
);

foreach ( $changes as $change )
{
	list($old_state,$new_state) = $change;

	echo "Moving projects from '$old_state' to '$new_state'\n";
	$res = mysql_query("
		SELECT nameofwork, state, projectid
		FROM projects
		WHERE state='$old_state'
	") or die(mysql_error());

	$n = mysql_num_rows($res);
	echo "$n projects to update...\n";
	echo "\n";

	while ( list($nameofwork,$state,$projectid) = mysql_fetch_row($res) )
	{
		echo "    Project $projectid: \"$nameofwork\"\n";
		mysql_query("
			UPDATE projects
			SET state='$new_state'
			WHERE projectid='$projectid'
		") or die(mysql_error());
		echo "\n";
	}
}
echo "done.\n";
echo "</pre>\n";
?>

<?PHP
$relPath = './pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'project_states.inc');
new dbConnect();

// The project states denoted by:
//     PROJ_PROOF_FIRST_VERIFY
//     PROJ_PROOF_SECOND_VERIFY
//     PROJ_SUBMIT_PG_UNAVAILABLE
//     PROJ_SUBMIT_PG_AVAILABLE
//     PROJ_SUBMIT_PG_POSTING
// have been discontinued.
// Find any projects in those states and move them to
// an appropriate state that still exists.

// Also, the proofing-round state values have changed format.

$case = "
	CASE state
		WHEN 'verify_1'                  THEN '".PROJ_P1_AVAILABLE."'
		WHEN 'verify_2'                  THEN '".PROJ_P2_AVAILABLE."'
		WHEN 'proj_submit_pgunavailable' THEN '".PROJ_POST_COMPLETE."'
		WHEN 'proj_submit_pgavailable'   THEN '".PROJ_POST_COMPLETE."'
		WHEN 'proj_submit_pgposting'     THEN '".PROJ_POST_COMPLETE."'

		WHEN 'bad_1'                     THEN '".PROJ_P1_BAD_PROJECT."'
		WHEN 'unavail_1'                 THEN '".PROJ_P1_UNAVAILABLE."'
		WHEN 'waiting_1'                 THEN '".PROJ_P1_WAITING_FOR_RELEASE."'
		WHEN 'avail_1'                   THEN '".PROJ_P1_AVAILABLE."'
		WHEN 'done_1'                    THEN '".PROJ_P1_COMPLETE."'
		WHEN 'bad_2'                     THEN '".PROJ_P2_BAD_PROJECT."'
		WHEN 'unavail_2'                 THEN '".PROJ_P2_UNAVAILABLE."'
		WHEN 'waiting_2'                 THEN '".PROJ_P2_WAITING_FOR_RELEASE."'
		WHEN 'avail_2'                   THEN '".PROJ_P2_AVAILABLE."'
		WHEN 'done_2'                    THEN '".PROJ_P2_COMPLETE."'

		ELSE state
	END
";

echo "<pre>\n";

mysql_query("
	UPDATE projects
	SET state=$case
") or die(mysql_error());
echo mysql_affected_rows(), " rows affected\n";

echo "done.\n";
echo "</pre>\n";
?>

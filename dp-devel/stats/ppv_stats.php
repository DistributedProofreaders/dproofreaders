<?
$relPath='../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

$title = _("Post-Processing Verification Statistics");
theme($title,'header');

echo "<br><br><h2>$title</h2>\n";

echo "<br>\n";

echo "<h3>" . _("Post-Processing Verifiers") . "</h3>\n";
echo "<h4>" . _("(Number of Projects Posted to PG)") . "</h4>\n";

$psd = get_project_status_descriptor('posted');
dpsql_dump_themed_ranked_query("
	SELECT checkedoutby as 'PPVer', count(  *  ) as 'Projects PPVd'
	FROM  `projects` , usersettings
	WHERE 1  AND checkedoutby != postproofer AND $psd->state_selector
		and checkedoutby = usersettings.username 
		and setting = 'post_proof_verifier' and value = 'yes' 
	GROUP  BY 1 
	ORDER  BY 2  DESC ");

echo "<br>\n";

echo _("Note that the above figures are as accurate as possible within the bounds of the current database structure");

echo "<br>\n";

theme("","footer");
?>

<?
$relPath='../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'theme.inc');

theme("Post-Processing Verification Statistics", "header");

echo "<br><br><h2>Post-Processing Verification Statistics</h2><br>\n";

echo "<br>\n";

echo "<h3>Post-Processing Verifiers</h3>\n";
echo "<h4>(Number of Projects Posted to PG</h4>\n";

dpsql_dump_ranked_query("
	SELECT checkedoutby as 'PPVer', count(  *  ) as 'Projects PPVd'
	FROM  `projects` , usersettings
	WHERE 1  AND checkedoutby != postproofer AND state LIKE  '%posted%'
		and checkedoutby = usersettings.username 
		and setting = 'post_proof_verifier' and value = 'yes' 
	GROUP  BY 1 
	ORDER  BY 2  DESC ");

echo "<br>\n";

echo _("Note that the above figures are as accurate as possible within the bounds of the current database structure");

echo "<br>\n";

theme("","footer");
?>


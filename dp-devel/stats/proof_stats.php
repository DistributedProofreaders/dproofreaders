<?
$relPath='../pinc/';
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'page_tally.php');

$title = _("Proofreading Statistics");
theme($title, 'header');

echo "<br><h2>" . _("Proofreading Statistics") . "</h2>\n";

echo "<a href='proj_proofed_graphs.php'>" . _("Projects Proofread Graphs") . "</a><br>";

echo "<br>\n";

echo "<h3>" . _("Total Projects Proofread") . "</h3>\n";

$state_selector = "
	(state LIKE 'proj_submit%'
		OR state LIKE 'proj_correct%'
		OR state LIKE 'proj_post%')
";


dpsql_dump_themed_query("
	SELECT
		SUM(num_projects) as 'Total Projects Proofread So Far'
	FROM project_state_stats WHERE $state_selector
	GROUP BY date ORDER BY date DESC LIMIT 1
");

echo "<br>\n";
echo "<br>\n";

echo "<h3>" . _("Most Prolific Proofreaders") . "</h3>\n";

if (isset($GLOBALS['pguser'])) 
// if user logged on
{

	// site managers get to see everyone
	if ( user_is_a_sitemanager() || user_is_proj_facilitator()) {
		$proofreader_expr = "username";
	}
	else
	{
		// hide names of users who don't want even logged on people to see their names
		$proofreader_expr = "IF(u_privacy = ".PRIVACY_ANONYMOUS.",'Anonymous', username)";
	}
} 
else
{

	// hide names of users who don't want unlogged on people to see their names
	$proofreader_expr = "IF(u_privacy != ".PRIVACY_PUBLIC.",'Anonymous', username)";
}
dpsql_dump_themed_ranked_query("
	SELECT
		$proofreader_expr AS 'Proofreader',
		$user_P_page_tally_column AS 'Pages Proofread'
	FROM $users_table_with_tallies
	WHERE $user_P_page_tally_column > 0
	ORDER BY 2 DESC, 1 ASC
	LIMIT 100
");

echo "<br>\n";

echo "<br>\n";

theme("","footer");
?>

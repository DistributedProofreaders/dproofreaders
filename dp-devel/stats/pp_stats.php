<?
$relPath='../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

$title = "Post-Processing Statistics";
theme($title,'header');

echo "<br><br><h2>$title</h2><br>\n";

echo "<a href='projects_Xed_graphs.php?which=PPd'>" . _("Projects PPd Graphs") . "</a><br>";

echo "<a href='PP_unknown.php'>" . _("Books with Mystery PPers") . "</a>";

echo "<br>\n";

echo "<h3>" . _("Total Projects Post-Processed Since Statistics were Kept") . "</h3>\n";

$psd = get_project_status_descriptor('PPd');
dpsql_dump_themed_query("
	SELECT
		SUM(num_projects) as 'Total Projects Post-Processed So Far'
	FROM project_state_stats WHERE $psd->state_selector
	GROUP BY date ORDER BY date DESC LIMIT 1
");

echo "<br>\n";
echo "<br>\n";

echo "<h3>" . _("Number of Distinct Post-Processors") . "</h3>\n";

dpsql_dump_themed_query("
	SELECT
		count(distinct postproofer) as 'Different PPers'
	FROM projects
");

echo "<br>\n";



echo "<h3>" . _("Most Prolific Post-Processors") . "</h3>\n";
echo "<h4>" . _("(Number of Projects Finished PPing)") . "</h4>\n";

$psd = get_project_status_descriptor('PPd');
dpsql_dump_themed_ranked_query("
	SELECT
		postproofer as 'PPer',
		count(*) as 'Projects Finished PPing'
	FROM projects
	WHERE $psd->state_selector
		AND postproofer is not null
	GROUP BY postproofer
	ORDER BY 2 DESC
");

echo "<br>\n";


echo "<h3>" . _("Most Prolific Post-Processors") . "</h3>\n";
echo "<h4>" . _("(Number of Projects Posted to PG)") . "</h4>\n";

$psd = get_project_status_descriptor('posted');
dpsql_dump_themed_ranked_query("
	SELECT
		postproofer as 'PPer',
		count(*) as 'Projects Posted to PG'
	FROM projects
	WHERE $psd->state_selector
	AND postproofer is not null
	GROUP BY postproofer
	ORDER BY 2 DESC
");

echo "<br>\n";



echo "<br>\n";

theme("","footer");
?>


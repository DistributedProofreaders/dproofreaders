<?
$relPath='../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'theme.inc');

theme("Post-Processing Statistics", "header");

echo "<br><br><h2>Post-Processing Statistics</h2><br>\n";

echo "<a href='proj_PPd_graphs.php'>Projects PPd Graphs</a><br>";

echo "<a href='PP_unknown.php'>Books with Mystery PPers</a>";

echo "<br>\n";

echo "<h3>Total Projects Post-Processed Since Statistics were Kept</h3>\n";

$state_selector = "
	(state LIKE 'proj_submit%'
		OR state LIKE 'proj_correct%'
		OR state LIKE 'proj_post_second%')
";


dpsql_dump_query("
	SELECT
		SUM(num_projects) as 'Total Projects Post-Processed So Far'
	FROM project_state_stats WHERE $state_selector
	GROUP BY date ORDER BY date DESC LIMIT 1
");

echo "<br>\n";
echo "<br>\n";

echo "<h3>Number of Distinct Post-Processors</h3>\n";

dpsql_dump_query("
	SELECT
		count(distinct postproofer) as 'Different PPers'
	FROM projects
");

echo "<br>\n";



echo "<h3>Most Prolific Post-Processors</h3>\n";
echo "<h4>(Number of Projects Finished PPing</h4>\n";

dpsql_dump_ranked_query("
	SELECT
		postproofer as 'PPer',
		count(*) as 'Projects Finished PPing'
	FROM projects
	WHERE (state LIKE 'proj_submit%'
		OR state LIKE 'proj_correct%'
		OR state LIKE 'proj_post_second%')
		AND postproofer is not null
	GROUP BY postproofer
	ORDER BY 2 DESC
	LIMIT 100
");

echo "<br>\n";


echo "<h3>Most Prolific Post-Processors</h3>\n";
echo "<h4>(Number of Projects Posted to PG</h4>\n";

dpsql_dump_ranked_query("
	SELECT
		postproofer as 'PPer',
		count(*) as 'Projects Posted to PG'
	FROM projects
	WHERE (state LIKE 'proj_submit%'
		OR state LIKE 'proj_correct%')
	AND postproofer is not null
	GROUP BY postproofer
	ORDER BY 2 DESC
	LIMIT 100
");

echo "<br>\n";



echo "<br>\n";

theme("","footer");
?>


<?
$relPath='../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'theme.inc');

theme("Project Manager Statistics", "header");

echo "<br><br><h2>Project Manager Statistics</h2><br>\n";

echo "<br>\n";

echo "<h3>Number of Distinct Project Managers</h3>\n";

dpsql_dump_query("
	SELECT
		count(distinct username) as 'Different PMs'
	FROM projects
");

echo "<br>\n";



echo "<h3>Most Prolific Project Managers</h3>\n";
echo "<h4>(Number of Projects Created</h4>\n";

dpsql_dump_ranked_query("
	SELECT
		username as 'PM',
		count(*) as 'Projects Created'
	FROM projects
	WHERE state not LIKE 'project_new%'
	GROUP BY username
	ORDER BY 2 DESC
	LIMIT 100
");

echo "<br>\n";


echo "<h3>Most Prolific Project Managers</h3>\n";
echo "<h4>(Number of Projects Posted to PG</h4>\n";

dpsql_dump_ranked_query("
	SELECT
		username as 'PM',
		count(*) as 'Projects Posted to PG'
	FROM projects
	WHERE (state LIKE 'proj_submit%'
		OR state LIKE 'proj_correct%')
	GROUP BY username
	ORDER BY 2 DESC
	LIMIT 100
");

echo "<br>\n";

echo "<br>\n";

theme("","footer");
?>


<?
$relPath='../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'theme.inc');

theme("Temporary Stats", "header");

echo "<br><br><h2>Temporary Stats</h2><br>\n";

echo "<br>\n";

echo "<h3>Projects with Genre = 'Art'</h3>\n";

echo "<br><br>If there are many projects with a common or similar Title or Author that are all going to the same genre,
suitable for a mass database change, feel free to lodge a Site Admin request 
at the <a href='http://www.pgdp.net/c/tasks.php'>Task Page</a>.<br><br>";

dpsql_dump_query("
	SELECT
		username as PM, nameofwork as Title, authorsname as Author , projectid 
	FROM projects 
	WHERE genre = 'Art'
	ORDER by 1, 2
");



theme("","footer");
?>


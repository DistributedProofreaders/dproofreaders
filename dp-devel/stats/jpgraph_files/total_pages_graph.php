<?
$relPath="./../../pinc/";
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'connect.inc');
include_once('common.inc');
new dbConnect();


///////////////////////////////////////////////////
//Total pages per day since beginning of stats
//query db and put results into arrays
$result = mysql_query("
	SELECT date, pages
	FROM pagestats
	ORDER BY date ASC
");

list($datax,$datay) = dpsql_fetch_columns($result);

draw_pages_graph(
	$datax,
	$datay,
	null,
	'daily',
	'increments',
	'Pages Done Per Day Since the Beginning of Statistics Collection',
	1440
);

?>

<?
$relPath="./../../pinc/";
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'connect.inc');
include_once('common.inc');
new dbConnect();


///////////////////////////////////////////////////
//Total pages by month since beginning of stats
//query db and put results into arrays
$result = mysql_query("
	SELECT CONCAT(year, '-', month), SUM(pages)
	FROM pagestats
	GROUP BY year, month
	ORDER BY year ASC, month ASC
");

list($datax,$datay) = dpsql_fetch_columns($result);

draw_pages_graph(
	$datax,
	$datay,
	null,
	'monthly',
	'increments',
	'Pages Done Each Month Since the Beginning of Statistics Collection',
	1440
);

?>

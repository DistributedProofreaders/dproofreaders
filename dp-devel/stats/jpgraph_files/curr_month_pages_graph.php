<?
$relPath="./../../pinc/";
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'connect.inc');
include_once('common.inc');
new dbConnect();

//Create pages per day graph for current month
$year  = date("Y");
$month = date("m");

$date_format = _('%Y-%m-%d');

//query db and put results into arrays
$result = mysql_query("
	SELECT DATE_FORMAT(date,'$date_format') AS fdate, pages, dailygoal
	FROM pagestats
	WHERE month = '$month' AND year = '$year'
	ORDER BY date
");

list($datax,$datay1,$datay2) = dpsql_fetch_columns($result);

draw_pages_graph(
	$datax,
	$datay1,
	$datay2,
	'daily',
	'increments',
	_('Pages Done Per Day for Current Month'),
	1
);

?>

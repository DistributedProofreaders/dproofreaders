<?
$relPath="./../../pinc/";
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'connect.inc');
include_once('common.inc');
new dbConnect();

$todaysTimeStamp = time();

//Create pages per day graph for current month
$day = date("d", $todaysTimeStamp);
$year  = date("Y", $todaysTimeStamp);
$month = date("m", $todaysTimeStamp);
$monthVar = date("F", $todaysTimeStamp);
$today = $year."-".$month."-".$day;

//query db and put results into arrays
$result = mysql_query("
	SELECT date, pages, dailygoal
	FROM pagestats
	WHERE month = '$month' AND year = '$year'
	ORDER BY date ASC
");

list($datax,$actual_per_day,$goal_per_day) = dpsql_fetch_columns($result);

$datay1 = array_accumulate( $actual_per_day );
$datay2 = array_accumulate( $goal_per_day );

// The accumulated 'actual' for today and subsequent days is bogus,
// so delete it.
for ( $i = 0; $i < count($datax); $i++ )
{
	if ( $datax[$i] >= $today )
	{
		unset($datay1[$i]);
	}
}

if (empty($datay1)) {
	$datay1[0] = 0;
}

draw_pages_graph(
	$datax,
	$datay1,
	$datay2,
	'daily',
	'cumulative',
	_('Cumulative Pages Completed for Current Month'),
	60
);

?>

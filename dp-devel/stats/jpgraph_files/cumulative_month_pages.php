<?
$relPath="./../../pinc/";
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

$i = 0;
$p = 0;
$g = 0;

while ($row = mysql_fetch_assoc($result)) {
	if (substr($row['date'], -2) < $day) { $datay1[$i] = $row['pages']+$p; }
	$datay2[$i] = $row['dailygoal']+$g;
	$datax[$i] = $row['date'];
	$p = $p+$row['pages'];
	$g = $g+$row['dailygoal'];
	$i++;
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
	"Cumulative Pages Completed for $monthVar $year",
	60
);

?>

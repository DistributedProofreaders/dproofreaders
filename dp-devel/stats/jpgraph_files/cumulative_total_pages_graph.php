<?
$relPath="./../../pinc/";
include_once($relPath.'connect.inc');
include_once('common.inc');
new dbConnect();

//Create pages per day graph for all pages done since stats started
$day = date("d");
$year  = date("Y");
$month = date("m");
$monthVar = date("F");
$today = $year."-".$month."-".$day;

//query db and put results into arrays
$result = mysql_query("SELECT pages,date, dailygoal FROM pagestats ORDER BY date ASC");
$mynumrows = mysql_numrows($result);
$i = 0;
$p = 0;
$g = 0;

while ($row = mysql_fetch_assoc($result)) {
	if ($row['date'] < $today) {$datay1[$i] = $row['pages']+$p;}
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
	'Cumulative Pages Completed Since Stats Started',
	3600
);

?>

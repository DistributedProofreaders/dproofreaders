<?
$relPath="./../../pinc/";
include_once($relPath.'connect.inc');
include_once('common.inc');
new dbConnect();

//Create pages per day graph for current month
$year  = date("Y");
$month = date("m");

//query db and put results into arrays
$result = mysql_query("
	SELECT date, pages, dailygoal
	FROM pagestats
	WHERE month = '$month' AND year = '$year'
	ORDER BY date
");

$mynumrows = mysql_numrows($result);
        $count = 0;
        while ($count < $mynumrows) {
        $datay1[$count] = mysql_result($result, $count, "pages");
        $datay2[$count] = mysql_result($result, $count, "dailygoal");
        $datax[$count] = strftime(_("%Y-%m-%d"),strtotime(mysql_result($result, $count, "date")));
            $count++;
        }

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

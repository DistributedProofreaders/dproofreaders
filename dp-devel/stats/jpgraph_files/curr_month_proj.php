<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($code_dir.'/stats/statestats.inc');
include_once($relPath.'gettext_setup.inc');
include_once('common.inc');

new dbConnect();

// Create "projects Xed per day" graph for current month

$psd = get_project_status_descriptor( $_GET['which'] );

$todaysTimeStamp = time();

$year  = date("Y", $todaysTimeStamp);
$month = date("m", $todaysTimeStamp);
$monthVar = _(date("F", $todaysTimeStamp));
$timeframe = "$monthVar $year";

// number of days this month - note that unlike project_state_stats, 
// which gets a row added for each new day just after midnight,
// pagestats is prepopulated with rows for the whole month
$result = mysql_query("SELECT max(day) as maxday FROM pagestats WHERE month = '$month' AND year = '$year'");
$maxday = mysql_result($result, 0, "maxday");

//query db and put results into arrays
$result = mysql_query("
	SELECT day, SUM(num_projects) AS PC
	FROM project_state_stats
	WHERE month = '$month' AND year = '$year' AND ($psd->state_selector)
	GROUP BY day
	ORDER BY day
");

$mynumrows = mysql_numrows($result);


if ($mynumrows) {
	$base = mysql_result($result,0 , "PC");
	$datay1[0] = $base;
} else {
	$datay1[0] = 0;
}
$datax[0] = 1;
$count = 1;


while ($count <= $maxday) {
	if ($count < $mynumrows) {
		$total = mysql_result($result, $count, "PC");
	       	$datay1[$count] = $total;
		$datay1[$count-1] = $total - $datay1[$count-1];
	} else {
		$datay1[$count-1] = 0;
		}
        $datax[$count] = $count + 1;
        $count++;
}

draw_projects_graph(
	$datax,
	$datay1,
	'increments',
	$psd->color,
	"$psd->per_day_title ($timeframe)",
	300
);

?>

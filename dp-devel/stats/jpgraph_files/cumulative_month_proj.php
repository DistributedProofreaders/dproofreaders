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
	SELECT day, SUM(num_projects) AS P
	FROM project_state_stats
	WHERE month = '$month' AND year = '$year' AND ($psd->state_selector)
	GROUP BY day
	ORDER BY day
");

$mynumrows = mysql_numrows($result);


$i = 0;
$p = 0;

// get base level, total at beginning of 1st day of month
	// snapshot is taken just after midnight,
	// so day = 1 has total at beginning of month

$row = mysql_fetch_assoc($result);
$base = $row['P'];

while ($row = mysql_fetch_assoc($result)) {
 	$datay1[$i] = $row['P'] - $base; 
	// snapshot is taken just *after* midnight,
	// so day7 minus day1 is number done over first six days of month
	$datax[$i] = $row['day'] - 1;
	$i++;
}

while ($i < $maxday) {
      $datay1[$i] = "";
      $datax[$i] = $i + 1;
      $i++;
}

if (empty($datay1)) {
	$datay1[0] = 0;
}

draw_projects_graph(
	$datax,
	$datay1,
	'cumulative',
	$psd->color,
	"$psd->cumulative_title ($timeframe)",
	60
);

?>

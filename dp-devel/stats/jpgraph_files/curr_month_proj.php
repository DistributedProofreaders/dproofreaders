<?
$relPath="./../../pinc/";
include_once($relPath.'f_dpsql.inc');
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

$maxday = get_number_of_days_in_current_month();

//query db and put results into arrays
$result = mysql_query("
	SELECT day, SUM(num_projects)
	FROM project_state_stats
	WHERE month = '$month' AND year = '$year' AND ($psd->state_selector)
	GROUP BY day
	ORDER BY day
");

list($datax,$y_cumulative) = dpsql_fetch_columns($result);

$datay1 = array_successive_differences($y_cumulative);

// Pad out the rest of the month
for ( $i = count($datay1); $i < $maxday; $i++ )
{
	$datax[$i] = $i+1;
	$datay1[$i] = 0;
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

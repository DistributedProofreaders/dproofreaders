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

list($datax,$y_num_projects) = dpsql_fetch_columns($result);

// get base level, total at beginning of 1st day of month
	// snapshot is taken just after midnight,
	// so day = 1 has total at beginning of month
	// Subtract that base level from each subsequent day's value
$datay1 = array_subtract_first_from_each($y_num_projects);
array_shift( $datay1 );

// Pad out the rest of the month
for ( $i = count($datay1); $i < $maxday; $i++ )
{
	$datax[$i] = $i+1;
	$datay1[$i] = "";
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

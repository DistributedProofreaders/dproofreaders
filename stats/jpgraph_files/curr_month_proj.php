<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()
include_once('common.inc');

$which = get_enumerated_param($_GET, 'which', null, $project_status_descriptors);

// Initialize the graph before anything else.
// This makes use of the jpgraph cache if enabled.
// Argument to init_projects_graph is the cache timeout in minutes.
$graph = init_projects_graph(60);


// Create "projects Xed per day" graph for current month

$psd = get_project_status_descriptor($which);

$todaysTimeStamp = time();

$year  = date("Y", $todaysTimeStamp);
$month = date("m", $todaysTimeStamp);
$monthVar = _(date("F", $todaysTimeStamp));
$timeframe = "$monthVar $year";

$maxday = get_number_of_days_in_current_month();

//query db and put results into arrays
$result = mysqli_query(DPDatabase::get_connection(), "
    SELECT DAYOFMONTH(date) as day, SUM(num_projects)
    FROM project_state_stats
    WHERE MONTH(date) = $month AND YEAR(date) = $year AND ($psd->state_selector)
    GROUP BY DAYOFMONTH(date)
    ORDER BY date
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
    $graph,
    $datax,
    $datay1,
    'increments',
    $psd->color,
    "$psd->per_day_title ($timeframe)"
);

// vim: sw=4 ts=4 expandtab

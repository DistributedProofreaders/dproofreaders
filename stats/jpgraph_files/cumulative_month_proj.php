<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()
include_once('common.inc');

$which = get_enumerated_param($_GET, 'which', null, $project_status_descriptors);

// Initialize the graph before anything else.
// This makes use of the jpgraph cache if enabled.
// Argument to init_projects_graph is the cache timeout in minutes.
$graph = init_pages_graph(60);

// Create "projects Xed per day" graph for current month

$psd = get_project_status_descriptor($which);

$todaysTimeStamp = time();

$year = date("Y", $todaysTimeStamp);
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

[$datax, $y_num_projects] = dpsql_fetch_columns($result);

// get base level, total at beginning of 1st day of month
    // snapshot is taken just after midnight,
    // so day = 1 has total at beginning of month
    // Subtract that base level from each subsequent day's value
$datay1 = array_subtract_first_from_each($y_num_projects);
array_shift($datay1);

// Pad out the rest of the month
for ($i = count($datay1); $i < $maxday; $i++) {
    $datax[$i] = $i + 1;
    $datay1[$i] = "";
}

draw_projects_graph(
    $graph,
    $datax,
    $datay1,
    'cumulative',
    $psd->color,
    "$psd->cumulative_title ($timeframe)"
);

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
$graph = init_projects_graph(60);

// Create "cumulative projects Xed per day" graph for all days
// since state stats started being recorded up to yesterday

$psd = get_project_status_descriptor($which);

$timeframe = _('since stats began');

//query db and put results into arrays
$result = mysqli_query(DPDatabase::get_connection(), "
    SELECT date, SUM(num_projects)
    FROM project_state_stats
    WHERE $psd->state_selector
    GROUP BY date
    ORDER BY date ASC
");

[$datax, $datay1] = dpsql_fetch_columns($result);

if (empty($datay1)) {
    $datay1[0] = 0;
}

draw_projects_graph(
    $graph,
    $datax,
    $datay1,
    'cumulative',
    $psd->color,
    "$psd->cumulative_title ($timeframe)"
);

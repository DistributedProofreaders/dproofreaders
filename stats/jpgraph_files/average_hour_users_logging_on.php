<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once('common.inc');

// Initialize the graph before anything else.
// This makes use of the jpgraph cache if enabled.
// Last argument to init_simple_bar_graph is the cache timeout in minutes.
$graph = init_simple_bar_graph(640, 400, 58);

///////////////////////////////////////////////////
//Numbers of users logging on in each hour of the day, since the start of stats


//query db and put results into arrays


$result = mysqli_query(DPDatabase::get_connection(), "
    SELECT hour, AVG(L_hour)
    FROM user_active_log
    GROUP BY hour
    ORDER BY hour
");

list($datax,$datay) = dpsql_fetch_columns($result);

draw_simple_bar_graph(
    $graph,
    $datax,
    $datay,
    1,
    _('Average number of users newly logged in each hour'),
    _('Fresh Logons')
);


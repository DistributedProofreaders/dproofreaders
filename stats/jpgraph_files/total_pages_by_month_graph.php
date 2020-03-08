<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'page_tally.inc'); // get_page_tally_names()
include_once($relPath.'misc.inc'); // get_enumerated_param()
include_once('common.inc');

$valid_tally_names = array_keys(get_page_tally_names());
$tally_name  = get_enumerated_param($_GET, 'tally_name', null, $valid_tally_names);

// Initialize the graph before anything else.
// This makes use of the jpgraph cache if enabled.
// Argument to init_pages_graph is the cache timeout in minutes.
$graph = init_pages_graph(60);

///////////////////////////////////////////////////
//Total pages by month since beginning of stats

$result = mysqli_query(DPDatabase::get_connection(),
    select_from_site_past_tallies_and_goals(
        $tally_name,
        "SELECT {year_month}, SUM(tally_delta), SUM(goal)",
        "",
        "GROUP BY 1",
        "ORDER BY 1",
        ""
    )
);

list($datax,$datay1,$datay2) = dpsql_fetch_columns($result);

draw_pages_graph(
    $graph,
    $datax,
    $datay1,
    $datay2,
    null,
    null,
    'monthly',
    'increments',
    _('Pages Done Each Month Since the Beginning of Statistics Collection')
);

// vim: sw=4 ts=4 expandtab

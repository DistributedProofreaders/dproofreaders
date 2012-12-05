<?php
$relPath="./../../pinc/";
include_once($relPath.'misc.inc');
include_once($relPath.'TallyBoard.inc');
include_once($relPath.'connect.inc');
include_once('common.inc');

$valid_tally_names = array_keys($page_tally_names);
$tally_name   = get_enumerated_param($_GET, 'tally_name', null, $valid_tally_names);
$holder_type  = get_enumerated_param($_GET, 'holder_type', null, array('U', 'T'));
$holder_id    = get_integer_param($_GET, 'holder_id', null, 0, null);
if (@$_GET['days_back'] == 'all') {
    $days_back = 'all';
} else {
    $days_back = get_integer_param($_GET, 'days_back', 30, 1, null);
}

// Initialize the graph before anything else.
// This makes use of the jpgraph cache if enabled.
// Last argument to init_simple_bar_graph is the cache timeout in minutes.
$graph = init_simple_bar_graph(600, 300, 60);

new dbConnect();

if ($days_back == "all")
{
    $min_timestamp = 0;
}
else
{
    $min_timestamp = time() - ($days_back * 86400);
}

$tallyboard = new TallyBoard( $tally_name, $holder_type );
$deltas = $tallyboard->get_deltas( $holder_id, $min_timestamp );

$datax = array();
$datay = array();
foreach ( $deltas as $timestamp => $tally_delta )
{
    $datax[] = date("n/j/Y", ($timestamp-1));
    $datay[] = $tally_delta;
}

$x_text_tick_interval = calculate_text_tick_interval( 'daily', count($datax) );

if (empty($datax) || empty($datay))
{
  $specimen = ($holder_type == 'U') ? 'user' : 'team';
  dpgraph_error("This $specimen has not completed any pages in this round.",600,300);
  die;
}

draw_simple_bar_graph(
    $graph,
    $datax,
    $datay,
    $x_text_tick_interval,
    'Pages Completed per Day',
    'Pages'
);

// vim: sw=4 ts=4 expandtab
?>

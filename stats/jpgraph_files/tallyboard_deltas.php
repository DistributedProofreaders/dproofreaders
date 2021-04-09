<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'page_tally.inc'); // get_page_tally_names() get_pages_per_day_for_past_n_days
include_once('common.inc');

$valid_tally_names = array_keys(get_page_tally_names());
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

$pages_per_day = get_pages_per_day_for_past_n_days($tally_name, $holder_type, $holder_id, $days_back);

$datax = array_keys($pages_per_day);
$datay = array_values($pages_per_day);

$x_text_tick_interval = calculate_text_tick_interval( 'daily', count($datax) );

if (empty($datax) || empty($datay))
{
    if($holder_type == 'U')
        $error = _("This user has not completed any pages in this round.");
    else
        $error = _("This team has not completed any pages in this round.");
    dpgraph_error($error, 600, 300);
    die;
}

draw_simple_bar_graph(
    $graph,
    $datax,
    $datay,
    $x_text_tick_interval,
    _('Pages Completed per Day'),
    _('Pages')
);


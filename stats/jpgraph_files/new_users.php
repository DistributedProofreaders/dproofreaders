<?php
// Graph the number of user registered per day.
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once('common.inc');

$time_interval_options = [
    'day' => [
        'format' => '%Y-%b-%d',
        'title' => _("Number of users registered per day"),
    ],
    'week' => [
        'format' => '%Y-%U',
        'title' => _("Number of users registered per week"),
    ],
    'month' => [
        'format' => '%Y-%M',
        'title' => _("Number of users registered per month"),
    ],
    'year' => [
        'format' => '%Y',
        'title' => _("Number of users registered per year"),
    ],
];

$time_interval = get_enumerated_param($_GET, 'time_interval', 'day', array_keys($time_interval_options));
$date_format = $time_interval_options[$time_interval]['format'];
$title = $time_interval_options[$time_interval]['title'];

$graph = init_simple_bar_graph(640, 400, 60);

$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT FROM_UNIXTIME(date_created,'$date_format'), COUNT(*)
    FROM users
    GROUP BY 1
    ORDER BY date_created
") or die(DPDatabase::log_error());

[$datax, $datay] = dpsql_fetch_columns($res);

$tick = calculate_text_tick_interval('daily', count($datay));

draw_simple_bar_graph(
    $graph,
    $datax,
    $datay,
    $tick,
    $title,
    _('# users')
);

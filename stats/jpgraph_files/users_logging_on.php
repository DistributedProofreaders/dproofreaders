<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()
include_once('common.inc');

$past      = get_enumerated_param($_GET, 'past', null, array('year', 'day'));
$preceding = get_enumerated_param($_GET, 'preceding', null, array('hour', 'day', 'week', 'fourweek'));

// For each hour in the $past interval,
// show the number of (distinct) users who had logged in
// (at least once) during the $preceding interval.

$seconds_per_day = 24 * 60 * 60;

switch ($past)
{
    case 'year':
        $min_timestamp = time() - 366 * $seconds_per_day;
        $date_format = '%Y-%b-%d';
        break;

    case 'day':
        $min_timestamp = time() - $seconds_per_day;
        $date_format = '%d %H';
        break;

    default:
        die("bad value for 'past'");
}

switch ($preceding)
{
    case 'hour':
        $title = _("Number of users newly logged in each hour");
        $column_name = 'L_hour';
        $cache_timeout = 58;
        break;

    case 'day':
        $title = _('Number of users newly logged in over 24 hours');
        $column_name = 'L_day';
        $cache_timeout = 58;
        break;

    case 'week':
        $title = _("Number of users newly logged in over 7 days");
        $column_name = 'L_week';
        $cache_timeout = 300;
        break;

    case 'fourweek':
        $title = _("Number of users newly logged in over 28 days");
        $column_name = 'L_4wks';
        $cache_timeout = 900;
        break;

    default:
        die("bad value for 'preceding'");
}

// Initialize the graph before making database call.
// This makes use of the jpgraph cache if enabled.
$graph = init_simple_bar_graph(640, 400, $cache_timeout);


///////////////////////////////////////////////////
//query db and put results into arrays

$result = mysqli_query(DPDatabase::get_connection(), "
    SELECT DATE_FORMAT(FROM_UNIXTIME(time_stamp),'$date_format'), $column_name
    FROM user_active_log 
    WHERE time_stamp >= $min_timestamp
    ORDER BY time_stamp
");

list($datax,$datay) = dpsql_fetch_columns($result);

$x_text_tick_interval = calculate_text_tick_interval( 'hourly', count($datay) );

draw_simple_bar_graph(
    $graph,
    $datax,
    $datay,
    $x_text_tick_interval,
    $title,
    _('Fresh Logons')
);


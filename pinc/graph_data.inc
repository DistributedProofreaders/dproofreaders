<?php

include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');

function get_graph_js_files()
{
    global $code_url;
    return [
        "$code_url/pinc/3rdparty/d3/d3.min.js",
        "$code_url/scripts/chart.js",
    ];
}

function cumulative_total_proj_summary_graph()
{
    global $project_status_descriptors;
    $max_num_data = 0;
    $data = [];

    foreach ($project_status_descriptors as $which) {
        $psd = get_project_status_descriptor($which);

        //query db and put results into arrays
        $result = mysqli_query(DPDatabase::get_connection(), "
            SELECT date, SUM(num_projects)
            FROM project_state_stats
            WHERE $psd->state_selector
            GROUP BY DATE
            ORDER BY date ASC
        ");

        [$datax, $datay] = dpsql_fetch_columns($result);

        if (empty($datay)) {
            $datay[0] = 0;
        }

        if (count($datay) > $max_num_data) {
            $max_num_data = count($datay);
        }

        $data[$which] = ["x" => $datax, "y" => $datay];
    }

    return [
        "data" => $data,
        "title" => _("Total Projects Created, Proofread, Post-Processed and Posted"),
    ];
}

function user_logging_on($past, $preceding)
{
    // For each hour in the $past interval,
    // show the number of (distinct) users who had logged in
    // (at least once) during the $preceding interval.

    $seconds_per_day = 24 * 60 * 60;

    switch ($past) {
    case 'year':
        $min_timestamp = time() - 366 * $seconds_per_day;
        $date_format = '%Y-%c-%d';
        break;

    case 'day':
        $min_timestamp = time() - $seconds_per_day;
        $date_format = '%H';
        break;

    default:
        die("bad value for 'past'");
}

    switch ($preceding) {
    case 'hour':
        $title = _("Number of users newly logged in each hour");
        $column_name = 'L_hour';
        break;

    case 'day':
        $title = _('Number of users newly logged in over 24 hours');
        $column_name = 'L_day';
        break;

    case 'week':
        $title = _("Number of users newly logged in over 7 days");
        $column_name = 'L_week';
        break;

    case 'fourweek':
        $title = _("Number of users newly logged in over 28 days");
        $column_name = 'L_4wks';
        break;

    default:
        die("bad value for 'preceding'");
}

    ///////////////////////////////////////////////////
    //query db and put results into arrays

    $result = mysqli_query(DPDatabase::get_connection(), "
    SELECT DATE_FORMAT(FROM_UNIXTIME(time_stamp),'$date_format'), $column_name
    FROM user_active_log 
    WHERE time_stamp >= $min_timestamp
    ORDER BY time_stamp
");

    [$datax, $datay] = dpsql_fetch_columns($result);

    return [
        "title" => $title,
        "axisLeft" => true,
        "data" => [
            _('Fresh Logons') => [
                "x" => $datax,
                "y" => $datay,
            ],
        ],
    ];
}

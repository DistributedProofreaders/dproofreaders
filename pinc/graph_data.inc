<?php

include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'page_tally.inc');

function get_graph_js_files()
{
    global $code_url;
    return [
        "$code_url/pinc/3rdparty/d3/d3.min.js",
        "$code_url/scripts/svgCharts.js",
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
        $sql = sprintf("SELECT date, SUM(num_projects)
            FROM project_state_stats
            WHERE %s
            GROUP BY DATE
            ORDER BY date ASC", $psd->state_selector);
        $result = DPDatabase::query($sql);

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

    $sql = sprintf("SELECT DATE_FORMAT(FROM_UNIXTIME(time_stamp), '%s'), $column_name
        FROM user_active_log
        WHERE time_stamp >= %d
        ORDER BY time_stamp",
        DPDatabase::escape($date_format),
        $min_timestamp);
    $result = DPDatabase::query($sql);

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

function tallyboard_deltas($tally_name, $holder_type, $holder_id, $days_back)
{
    $valid_tally_names = array_keys(get_page_tally_names());

    $pages_per_day = get_pages_per_day_for_past_n_days($tally_name, $holder_type, $holder_id, $days_back);

    $datax = array_keys($pages_per_day);
    $datay = array_values($pages_per_day);

    $error = null;
    if (empty($datax) || empty($datay)) {
        if ($holder_type == 'U') {
            $error = _("This user has not completed any pages in this round.");
        } else {
            $error = _("This team has not completed any pages in this round.");
        }
    }

    return [
        "title" => _('Pages Completed per Day'),
        "axisLeft" => true,
        "bottomLegend" => $error,
        "width" => 600,
        "height" => 300,
        "xAxisHeight" => 65,
        "data" => [
            is_null($error) ? _('Pages') : $error => [
                "x" => $datax,
                "y" => $datay,
            ],
        ],
    ];
}

function new_users($time_interval)
{
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

    $date_format = $time_interval_options[$time_interval]['format'];
    $title = $time_interval_options[$time_interval]['title'];

    $sql = sprintf("SELECT FROM_UNIXTIME(date_created, '%s'), COUNT(*)
      FROM users
      GROUP BY 1
      ORDER BY date_created", DPDatabase::escape($date_format));
    $res = DPDatabase::query($sql);

    [$datax, $datay] = dpsql_fetch_columns($res);

    return [
        "title" => $title,
        "xAxisHeight" => 90,
        "data" => [
            _('# users') => [
                "x" => $datax,
                "y" => $datay,
            ],
        ],
    ];
}

function average_hour_users_logging_on()
{
    ///////////////////////////////////////////////////
    //Numbers of users logging on in each hour of the day, since the start of stats

    //query db and put results into arrays
    $sql = "SELECT hour, AVG(L_hour)
      FROM user_active_log
      GROUP BY hour
      ORDER BY hour";
    $result = DPDatabase::query($sql);

    [$datax, $datay] = dpsql_fetch_columns($result);

    return [
        "title" => _('Average number of users newly logged in each hour'),
        "yAxisWidth" => "60",
        "data" => [
            _('Fresh Logons') => [
                "x" => $datax,
                "y" => $datay,
            ],
        ],
    ];
}

function users_by_language()
{
    $res = DPDatabase::query("SELECT IFNULL(LEFT(u_intlang, 2), '') AS intlang, COUNT(*) AS num
      FROM users
      GROUP BY intlang
      ORDER BY num DESC");

    $x = [];
    $y = [];

    while ($r = mysqli_fetch_assoc($res)) {
        array_push($x, (
        $r['intlang'] ?
            dgettext("iso_639", eng_name($r['intlang'])) :
            _("Browser default")
        )
    );
        array_push($y, $r['num']);
    }

    $title = _("Number of users per user interface language");
    return [
        "title" => $title,
        "xAxisHeight" => 100,
        "yAxisWidth" => 75,
        "data" => [
            _('Interface Language') => [
                "x" => $x,
                "y" => $y,
            ],
        ],
    ];
}

function users_by_country()
{
    $res = DPDatabase::query("SELECT SUBSTRING_INDEX(email, '.' ,-1) AS domain,
        COUNT(*) AS num
        FROM users
        WHERE email LIKE '%@%.%'
        GROUP BY domain
        ORDER BY num DESC");

    $x = [];
    $y = [];

    while ($r = mysqli_fetch_assoc($res)) {
        array_push($x, $r['domain']);
        array_push($y, $r['num']);
    }

    $title = _("Number of users per country");
    return [
        "title" => $title,
        "yAxisWidth" => 75,
        "data" => [
            _('Country') => [
                "x" => $x,
                "y" => $y,
            ],
        ],
    ];
}

function array_successive_differences($arr)
// Return the array defined by:
//     $result[$i] = $arr[$i+1] - $arr[$i]
{
    $result = [];
    foreach ($arr as $key => $value) {
        if (isset($prev_key)) {
            $result[$prev_key] = $value - $prev_value;
        }
        $prev_key = $key;
        $prev_value = $value;
    }
    return $result;
}

function get_number_of_days_in_current_month()
{
    $current = getdate();
    // The last day of this month is the "zeroth" day of next month.
    $last_day_timestamp =
        mktime(0, 0, 0, $current['mon'] + 1, 0, $current['year']);
    $last_day = getdate($last_day_timestamp);
    return $last_day['mday'];
}

function curr_month_proj($which)
{
    // Create "projects Xed per day" graph for current month

    $psd = get_project_status_descriptor($which);

    $todaysTimeStamp = time();

    $year = date("Y", $todaysTimeStamp);
    $month = date("m", $todaysTimeStamp);
    $monthVar = _(date("F", $todaysTimeStamp));
    $timeframe = "$monthVar $year";

    $maxday = get_number_of_days_in_current_month();

    //query db and put results into arrays
    $sql = sprintf("SELECT DAYOFMONTH(date) as day, SUM(num_projects)
      FROM project_state_stats
      WHERE MONTH(date) = %d AND YEAR(date) = %d AND (%s)
      GROUP BY DAYOFMONTH(date)
      ORDER BY date",
      DPDatabase::escape($month),
      DPDatabase::escape($year),
      $psd->state_selector);
    $result = DPDatabase::query($sql);

    [$datax, $y_cumulative] = dpsql_fetch_columns($result);

    $datay1 = array_successive_differences($y_cumulative);

    // Pad out the rest of the month
    for ($i = count($datay1); $i < $maxday; $i++) {
        $datax[$i] = $i + 1;
        $datay1[$i] = 0;
    }

    return [
        "title" => "$psd->per_day_title ($timeframe)",
        "yAxisWidth" => 75,
        "data" => [
            _('Projects') => [
                "x" => $datax,
                "y" => $datay1,
            ],
        ],
    ];
}

function array_subtract_first_from_each($arr)
// Return the array defined by:
//     $result[$i] = $arr[$i] - $arr[0]
{
    $result = [];
    foreach ($arr as $key => $value) {
        if (count($result) == 0) {
            $first_value = $value;
        }
        $result[$key] = $value - $first_value;
    }
    return $result;
}

function cumulative_month_proj($which)
{
    // Create "projects Xed per day" graph for current month

    $psd = get_project_status_descriptor($which);

    $todaysTimeStamp = time();

    $year = date("Y", $todaysTimeStamp);
    $month = date("m", $todaysTimeStamp);
    $monthVar = _(date("F", $todaysTimeStamp));
    $timeframe = "$monthVar $year";

    $maxday = get_number_of_days_in_current_month();

    //query db and put results into arrays
    $sql = sprintf("SELECT DAYOFMONTH(date) as day, SUM(num_projects)
      FROM project_state_stats
      WHERE MONTH(date) = %s AND YEAR(date) = %s AND (%s)
      GROUP BY DAYOFMONTH(date)
      ORDER BY date",
      DPDatabase::escape($month),
      DPDatabase::escape($year),
      $psd->state_selector);
    $result = DPDatabase::query($sql);

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

    return [
        "title" => "$psd->cumulative_title ($timeframe)",
        "yAxisWidth" => 75,
        "data" => [
            _('Projects') => [
                "x" => $datax,
                "y" => $datay1,
            ],
        ],
    ];
}

function total_proj_graph($which)
{
    //Create "projects Xed per day" graph for all known history

    $psd = get_project_status_descriptor($which);

    $timeframe = _('since stats began');


    //query db and put results into arrays
    $result = DPDatabase::query("SELECT date, SUM(num_projects)
      FROM project_state_stats
      WHERE $psd->state_selector
      GROUP BY date
      ORDER BY date");

    [$datax, $y_cumulative] = dpsql_fetch_columns($result);

    $datay1 = array_successive_differences($y_cumulative);
    $datay1[] = 0;

    return [
        "title" => "$psd->per_day_title ($timeframe)",
        "xAxisHeight" => 75,
        "yAxisWidth" => 75,
        "data" => [
            _('Projects') => [
                "x" => $datax,
                "y" => $datay1,
            ],
        ],
    ];
}

function cumulative_total_proj_graph($which)
{
    // Create "cumulative projects Xed per day" graph for all days
    // since state stats started being recorded up to yesterday

    $psd = get_project_status_descriptor($which);

    $timeframe = _('since stats began');

    //query db and put results into arrays
    $result = DPDatabase::query("SELECT date, SUM(num_projects)
    FROM project_state_stats
    WHERE $psd->state_selector
    GROUP BY date
    ORDER BY date ASC");

    [$datax, $datay1] = dpsql_fetch_columns($result);

    if (empty($datay1)) {
        $datay1[0] = 0;
    }

    return [
        "title" => "$psd->cumulative_title ($timeframe)",
        "xAxisHeight" => 75,
        "yAxisWidth" => 75,
        "data" => [
            _('Projects') => [
                "x" => $datax,
                "y" => $datay1,
            ],
        ],
    ];
}

<?php
include_once("exceptions.inc");

// DP API v1 -- Stats

//---------------------------------------------------------------------------
// stats/site

function api_v1_stats_site($method, $data, $query_params)
{
    $res = DPDatabase::query("SELECT COUNT(*) FROM users");
    list($registered_users) = mysqli_fetch_row($res);

    $stats = [
        "server_time" => date(DATE_ATOM),
        "registered_users" => $registered_users,
    ];

    foreach ( array(1,7,30) as $days_back )
    {
        $res = DPDatabase::query(sprintf("
            SELECT COUNT(*)
            FROM users
            WHERE t_last_activity > UNIX_TIMESTAMP() - %d * 24*60*60
        ", $days_back));
        list($num_users) = mysqli_fetch_row($res);
        $key = sprintf("active_users_%d_day", $days_back);
        $stats[$key] = $num_users;
    }
    return $stats;
}

//---------------------------------------------------------------------------
// stats/site/:roundid

function api_v1_stats_site_round($method, $data, $query_params)
{
    $round = $data[":roundid"];

    $stats = get_site_page_tally_summary($round->id);
    return [
        "today_goal" => $stats->curr_day_goal,
        "today_actual" => $stats->curr_day_actual,
        "yesterday_goal" => $stats->prev_day_goal,
        "yesterday_actual" => $stats->prev_day_actual,
        "month_goal" => $stats->curr_month_goal,
        "month_actual" => $stats->curr_month_actual,
    ];
}

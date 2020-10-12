<?php
include_once("exceptions.inc");

// DP API v1 -- Stats

//---------------------------------------------------------------------------
// stats/site

function api_v1_stats_site($method, $data, $query_params)
{
    $res = DPDatabase::query("SELECT COUNT(*) FROM users");
    list($registered_users) = mysqli_fetch_row($res);

    return [
        "server_time" => date(DATE_ATOM),
        "registered_users" => $registered_users,
    ];
}

//---------------------------------------------------------------------------
// stats/site/rounds

function render_round_stats($round_id)
{
    $stats = get_site_page_tally_summary($round_id);
    return [
        "today_goal" => $stats->curr_day_goal,
        "today_actual" => $stats->curr_day_actual,
        "yesterday_goal" => $stats->prev_day_goal,
        "yesterday_actual" => $stats->prev_day_actual,
        "month_goal" => $stats->curr_month_goal,
        "month_actual" => $stats->curr_month_actual,
    ];
}

function api_v1_stats_site_rounds($method, $data, $query_params)
{
    global $Round_for_round_id_;

    $return = [];
    foreach(array_keys($Round_for_round_id_) as $round_id)
    {
        $return[$round_id] = render_round_stats($round_id);
    }

    return $return;
}

//---------------------------------------------------------------------------
// stats/site/rounds/:roundid

function api_v1_stats_site_round($method, $data, $query_params)
{
    $round = $data[":roundid"];

    return render_round_stats($round->id);
}

<?php
include_once("exceptions.inc");

// DP API v1 -- Stats

//===========================================================================
// Validators

function validate_round($roundid)
{
    global $Round_for_round_id_;

    if(!in_array($roundid, array_keys($Round_for_round_id_)))
    {
        throw new NotFoundError("Invalid round");
    }
    return $Round_for_round_id_[$roundid];
}

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

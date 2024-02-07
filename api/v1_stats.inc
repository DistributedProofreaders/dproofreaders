<?php
include_once("exceptions.inc");

// DP API v1 -- Stats

//---------------------------------------------------------------------------
// stats/site

function api_v1_stats_site($method, $data, $query_params)
{
    $res = DPDatabase::query("SELECT COUNT(*) FROM users");
    [$registered_users] = mysqli_fetch_row($res);

    $stats = [
        "server_time" => date(DATE_ATOM),
        "registered_users" => (int)$registered_users,
    ];

    foreach ([1, 7, 30] as $days_back) {
        $sql = sprintf(
            "
            SELECT COUNT(*)
            FROM users
            WHERE t_last_activity > UNIX_TIMESTAMP() - %d * 24*60*60
            ",
            $days_back
        );
        $res = DPDatabase::query($sql);
        [$num_users] = mysqli_fetch_row($res);
        $key = sprintf("active_users_%d_day", $days_back);
        $stats[$key] = (int)$num_users;
    }
    return $stats;
}

//---------------------------------------------------------------------------
// stats/site/projects_states
// Produces output like
// ```
// {
//    "project_new": 224,
//    "project_bad": 0,
//    "F1.proj_avail": 11,
//    "F2.proj_avail": 7,
//    "F2.proj_unavail": 2,
//    "F2.proj_waiting": 1,
//    ...
//    "P3.proj_unavail": 1,
//    "proj_post_first_available": 4,
//    "proj_post_first_checked_out": 24,
//    "proj_post_first_unavailable": 3,
//    "proj_post_second_available": 4,
//    "proj_post_second_checked_out": 2,
//    "proj_delete": 46,
// }```
function api_v1_stats_site_projects_states($method, $data, $query_params)
{
    // Make sure all project state statistics are provided.
    $output = [];
    global $PROJECT_STATES_IN_ORDER;
    foreach ($PROJECT_STATES_IN_ORDER as $s) {
        $output[$s] = 0;
    }

    // Fill in the populated project states from the DB
    $sql = "
        SELECT state, COUNT(*)
        FROM projects
        GROUP BY state
    ";
    $result = DPDatabase::query($sql);
    while ([$project_state, $count] = mysqli_fetch_row($result)) {
        $output[$project_state] = (int)$count;
    }
    return $output;
}

function api_v1_stats_site_projects_stages($method, $data, $query_params)
{
    global $Stage_for_id_;
    global $Round_for_project_state_;
    global $Pool_for_state_;

    // Make sure all project stage statistics are provided.
    $output = [];
    foreach ($Stage_for_id_ as $s) {
        $output[$s->id] = 0;
    }

    $sql = "
        SELECT state, COUNT(*)
        FROM projects
        GROUP BY state
    ";
    $result = DPDatabase::query($sql);
    while ([$project_state, $count] = mysqli_fetch_row($result)) {
        // If the stage is in a round, add it to the round's count
        $r = array_get($Round_for_project_state_, $project_state, null);
        if ($r) {
            $output[$r->id] += (int)$count;
            continue;
        }
        // If the stage is in a pool, add it to the pool's count
        $p = array_get($Pool_for_state_, $project_state, null);
        if ($p) {
            $output[$p->id] += (int)$count;
            continue;
        }

    }

    // Handle SR separately. It's a special pool.
    $sql = "
        SELECT COUNT(*)
        FROM projects
        WHERE state = '" . PROJ_POST_FIRST_CHECKED_OUT . "'
            AND smoothread_deadline > UNIX_TIMESTAMP()
    ";
    $result = DPDatabase::query($sql);
    [$count] = mysqli_fetch_row($result);
    $output['SR'] = (int)$count;

    return $output;
}

//---------------------------------------------------------------------------
// stats/site/rounds

function render_round_stats($round_id)
{
    $stats = get_site_page_tally_summary($round_id);
    $tallyboard = new TallyBoard($round_id, 'U');
    return [
        "today_goal" => (int)$stats->curr_day_goal,
        "today_actual" => (int)$stats->curr_day_actual,
        "yesterday_goal" => (int)$stats->prev_day_goal,
        "yesterday_actual" => (int)$stats->prev_day_actual,
        "month_goal" => (int)$stats->curr_month_goal,
        "month_actual" => (int)$stats->curr_month_actual,
        "positive_users" => (int)$tallyboard->get_num_holders_with_positive_tally(),
    ];
}

function api_v1_stats_site_rounds($method, $data, $query_params)
{
    global $Round_for_round_id_;

    $return = [];
    foreach (array_keys($Round_for_round_id_) as $round_id) {
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

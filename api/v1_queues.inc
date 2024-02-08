<?php
include_once($relPath.'release_queue.inc');
include_once($relPath.'user_is.inc');
include_once("exceptions.inc");
include_once("api_common.inc");

// DP API v1 -- Queues

//---------------------------------------------------------------------------
// queues -- list queues, optionally filtered by round
function api_v1_queues($method, $data, $query_params)
{
    $roundid = array_get($query_params, "roundid", null);
    $round = !is_null($roundid) ? validate_round($roundid, null) : null;

    $show = get_enumerated_param($query_params, "show", "all", ["enabled", "populated", "all"]);

    // Fields we  can query on
    $valid_fields = [
        "name",
        "group",
    ];

    $valid_return_fields = [
        "id",
        "round_id",
        "group",
        "ordering",
        "name",
        "enabled",
        "populated",
        "length",
        "unheld_length",
        "comment",
    ];

    if (user_can_see_queue_settings()) {
        array_push($valid_return_fields, "project_selector", "projects_target", "pages_target");
    }

    // restrict to list of desired fields, if set
    $return_fields = array_get_as_array($query_params, "field", $valid_return_fields);
    $return_fields = array_intersect($return_fields, $valid_return_fields);

    $name_selector = array_get($query_params, "name", null);
    $group_selector = array_get($query_params, "group", null);

    $output = [];
    foreach (fetch_queues_data($round, $show, false, $name_selector, $group_selector) as $queue_data) {
        array_remove_invalid_fields($queue_data, $return_fields);
        $output[] = $queue_data;
    }
    return $output;
}

//---------------------------------------------------------------------------
// queues/:queueid -- return release queue info
function api_v1_queue($method, $data, $query_params)
{
    $queue = $data[":queueid"];

    return $queue;
}

//---------------------------------------------------------------------------
// queue/:queueid/stats -- return release queue stats
function api_v1_queue_stats($method, $data, $query_params)
{
    $queue = $data[":queueid"];

    $round = validate_round($queue["round_id"], null);
    $name = $queue["name"];

    return fetch_queue_stats_data($round, $name);
}

//---------------------------------------------------------------------------
// queue/:queueid/projects -- list projects in release queue
function api_v1_queue_projects($method, $data, $query_params)
{
    $queue = $data[":queueid"];
    $unheld_only = (bool) get_flag_value($query_params, "unheld_only");

    $valid_return_fields = [
        "projectid",
        "title",
        "author",
        "languages",
        "genre",
        "difficulty",
        "last_state_change_time",
    ];

    // restrict to list of desired fields, if set
    $return_fields = array_get_as_array($query_params, "field", $valid_return_fields);
    $return_fields = array_intersect($return_fields, $valid_return_fields);

    $round = validate_round($queue["round_id"], null);

    $output = [];
    foreach (fetch_queue_projects_data($round, $queue["project_selector"], $unheld_only) as $proj_data) {
        array_remove_invalid_fields($proj_data, $return_fields);
        if (isset($proj_data["last_state_change_time"])) {
            $proj_data["last_state_change_time"] = date(DATE_ATOM, $proj_data["last_state_change_time"]);
        }
        $output[] = $proj_data;
    }
    return $output;
}

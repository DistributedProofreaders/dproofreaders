<?php

// Global variable that specifies when we alert PPers about their
// outstanding projects. Time is calculated as this many days prior
// to midnight of the 1st of the current month.
global $pp_alert_threshold_days;
$pp_alert_threshold_days = 90;

function count_pp_projects_past_threshold($PPer = null)
{
    $columns = ["count(*) AS total"];
    $result = _run_pp_threshold_query_result(PROJ_POST_FIRST_CHECKED_OUT, $columns, $PPer);

    [$total] = mysqli_fetch_row($result);
    return $total;
}

function count_ppv_projects_past_threshold($PPVer = null)
{
    $columns = ["count(*) AS total"];
    $result = _run_pp_threshold_query_result(PROJ_POST_SECOND_CHECKED_OUT, $columns, $PPVer);

    [$total] = mysqli_fetch_row($result);
    return $total;
}

function get_pp_projects_past_threshold($PPer = null)
{
    $columns = [
        "nameofwork",
        "checkedoutby",
        "projects.projectid",
        "authorsname",
        "modifieddate",
        "user_project_info.t_latest_home_visit AS lastvisitdate",
    ];

    $ordering_criteria = "checkedoutby, modifieddate";

    $result = _run_pp_threshold_query_result(PROJ_POST_FIRST_CHECKED_OUT, $columns, $PPer, $ordering_criteria);

    $projects_grouped_by_PPer = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $row_PPer = $row["checkedoutby"];
        if (!isset($projects_grouped_by_PPer[$row_PPer])) {
            $projects_grouped_by_PPer[$row_PPer] = [];
        }
        $projects_grouped_by_PPer[$row_PPer][] = $row;
    }

    if ($PPer) {
        return $projects_grouped_by_PPer[$PPer] ?? [];
    } else {
        return $projects_grouped_by_PPer;
    }
}

// internal function to avoid duplicating the SQL logic that determines
// projects past the PP alert threshold
function _run_pp_threshold_query_result($state, $columns, $user, $ordering_criteria = null)
{
    global $pp_alert_threshold_days;

    $column_selector = implode(", ", $columns);
    $user_selector = $user ? "AND checkedoutby = '$user'" : "";
    $order_by_clause = $ordering_criteria ? "ORDER BY $ordering_criteria" : "";

    $cutoff_timestamp = strtotime("midnight first day of this month") -
        ($pp_alert_threshold_days * 60 * 60 * 24);

    // Because PPers may have projects checked out to them automatically,
    // user_project_info.t_latest_home_visit may not always be a good
    // timestamp to use to determine the cutoff. Use the more recent value
    // of t_latest_home_visit and modifieddate.
    // To handle the (very rare) case where the user has never visited the
    // project page, we need the IFNULL() function to map the visit time to 0.
    $sql = "
        SELECT
            $column_selector
        FROM projects LEFT OUTER JOIN user_project_info ON
            projects.projectid = user_project_info.projectid AND
            projects.checkedoutby = user_project_info.username
        WHERE state = '$state' AND
            GREATEST(IFNULL(user_project_info.t_latest_home_visit, 0), modifieddate) <= $cutoff_timestamp
            $user_selector
        $order_by_clause
    ";
    return DPDatabase::query($sql);
}

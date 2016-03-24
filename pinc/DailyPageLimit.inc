<?php
include_once($relPath."Project.inc");
include_once($relPath."misc.inc"); // functions that might be used in project_selector

class DailyPageLimit
{
    function DailyPageLimit( $name, $project_selector, $daily_limit )
    {
        $this->name = $name;
        $this->project_selector = $project_selector;
        $this->daily_limit = $daily_limit;
    }

    function applies_to_project($project)
    {
        $str_to_eval = "return $this->project_selector;";
        // echo "<br>\nevaluating: $str_to_eval<br>\n";
        $result = eval($str_to_eval);
        // echo "returns "; var_dump($result);
        return $result;
    }

    function get_current_count_for_user($username)
    {
        $res = mysqli_query(DPDatabase::get_connection(), "
            SELECT current_count
            FROM daily_page_limit_user_counts
            WHERE
                limit_name = '$this->name'
                AND
                username = '$username'
        ") or die(mysqli_error(DPDatabase::get_connection()));
        if (mysqli_num_rows($res) == 0)
        {
            return 0;
        }
        else
        {
            list($count) = mysqli_fetch_row($res);
            return $count;
        }
    }

    function user_count_has_reached_limit($username)
    {
        $user_count = $this->get_current_count_for_user($username);
        return ($user_count >= $this->daily_limit);
    }

    function adjust_user_count($username, $adjustment)
    {
        $res = mysqli_query(DPDatabase::get_connection(), "
            INSERT INTO daily_page_limit_user_counts
            SET
                limit_name = '$this->name',
                username = '$username',
                current_count = $adjustment
            ON DUPLICATE KEY UPDATE
                current_count = current_count + $adjustment
        ") or die(mysqli_error(DPDatabase::get_connection()));
        // $n_rows = mysqli_affected_rows($res);
        // $n_rows == 0 means an existing row was set to its current values
        // $n_rows == 1 means a new row was inserted
        // $n_rows == 2 means an existing row was updated
    }
}

// We assume that there will be very few DPLs in the system,
// and a project will rarely if ever be targeted by more than one DPL.

function dpls_get_all()
{
    static $all_dpls = NULL;
    if (is_null($all_dpls))
    {
        // XXX get from DB
        $all_dpls = array(
            new DailyPageLimit('F1', '$project->state == "F1.proj_avail" && !startswith($project->nameofwork, "F1 self-evaluation")', 3)
        );
    }
    return $all_dpls;
}

function dpls_get_applicable($project)
// Return an array of DailyPageLimit objects,
// one for each page limit that currently applies to this project.
{
    $all_dpls = dpls_get_all();

    $applicable_dpls = array();
    foreach ($all_dpls as $dpl)
    {
        if ($dpl->applies_to_project($project))
        {
            $applicable_dpls[] = $dpl;
        }
    }
    return $applicable_dpls;
}

function dpls_reset_user_counts()
{
    mysqli_query(DPDatabase::get_connection(), "
        TRUNCATE TABLE daily_page_limit_user_counts
    ") or die( mysqli_error(DPDatabase::get_connection()) );
}

function dpls_check_user_count($project, $username)
// Of the DPLs (if any) applicable to $project,
// return those (if any) for which the user has reached the limit.
{
    $dpls_reached = array();
    foreach ( dpls_get_applicable($project) as $dpl )
    {
        if ($dpl->user_count_has_reached_limit($username))
        {
            $dpls_reached[] = $dpl;
        }
    }
    return $dpls_reached;
}

function dpls_adjust_user_count($project, $username, $adjustment)
// Adjust the user's counts in all DPLs that apply to $project.
{
    foreach ( dpls_get_applicable($project) as $dpl )
    {
        $dpl->adjust_user_count($username, $adjustment);
    }
}

// vim: sw=4 ts=4 expandtab

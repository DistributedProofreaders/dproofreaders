<?PHP
include_once($relPath.'project_states.inc');
include_once($relPath.'RoundDescriptor.inc');

function get_n_pages_proofed( $start_ts, $end_ts, &$n_projects )
// Return the total number of pages proofed between the two timestamps.
// (Specifically, pages that (appear to) have been saved-as-done at some time t,
// where $start_ts <= t < $end_ts.)
//
// If $end_ts is null, the t < $end_ts constraint is dropped.
// The caller could achieve the same effect by passing in a sufficiently
// large value for $end_ts, but instead, this feature:
// -- relieves the caller from having to determine such a value, and
// -- potentially improves query performance.
//
// $n_projects (an 'out' parameter) will be assigned the number of projects
// examined.
//
// (Takes about ?? seconds.)
{
    $total_n_pages_proofed = 0;

    // Only consider projects that have not been archived.
    // Also exclude:
    //     new projects,
    //     projects in PPV,
    //     projects that became checked out for PP or waiting or unavailable before midnight,
    //     and posted projects.
    // The assumption is that a book won't be proofing in the morning
    // and posted to PG before midnight.
    // When we have the big table this will be rewritten and will be able to run
    // MUCH faster

    // Note that for backfilling arbitrary dates (only to be done on a
    // non-production server, or when we have the big table in place,
    // would need to rewrite so as to include all projects.
    // Even the archive bit will lead to wrong totals if we were calculating,
    // say, the total from a day last month.

    $res = mysql_query("
        SELECT projectid, state
        FROM projects
        WHERE archived = '0'
            AND (state != '".PROJ_NEW."')
            AND (state != '".PROJ_SUBMIT_PG_POSTED."')
            AND (state != '".PROJ_POST_SECOND_AVAILABLE."')
            AND (state != '".PROJ_POST_SECOND_CHECKED_OUT."')
            AND (state != '".PROJ_POST_FIRST_CHECKED_OUT."'          OR modifieddate > $start_ts)
            AND (state != '".PROJ_P1_WAITING_FOR_RELEASE."' OR modifieddate > $start_ts)
            AND (state != '".PROJ_POST_FIRST_UNAVAILABLE."'          OR modifieddate > $start_ts)
    ")
        or die(mysql_error());

    $n_projects = mysql_num_rows($res);

    while ( $project_row = mysql_fetch_array($res) )
    {
        list($projectid,$project_state) = $project_row;

        $n_proofings_for_page = sql_n_proofings_for_page( $start_ts, $end_ts, $project_state );

        $result = mysql_query("SELECT SUM($n_proofings_for_page) FROM $projectid");

        if (!$result)
        {
            // Probably the project's page-table does not exist.
            // Not sure why.
            $n_pages_proofed = 0;
            continue;
        } else {
            list($n_pages_proofed) = mysql_fetch_row($result);
        }

        mysql_free_result($result);

        $total_n_pages_proofed += $n_pages_proofed;
    }

    return $total_n_pages_proofed;
}

function sql_n_proofings_for_page( $start_ts, $end_ts, $project_state )
// Return an SQL expression that, for a given page,
// yields the number of 'proofings' that the page achieved
// within the time constraints.
{
    // A page can be proofed (i.e., saved as done) in more than one round
    // in a day (or any given time interval).  So for a given page, we want
    // to count one proofing for each round in which:
    // -- it has been saved as done, and
    // -- the "round time" is within the interval.
    //
    // We can assume that the page has been saved in all rounds preceding
    // the current one, and we can tell from its state whether it has been
    // saved in the current one.

    $prd = get_PRD_for_project_state($project_state);
    if (!is_null($prd))
    {
        // The project is in a particular page-editing round.

        // Can we count a proofing for the current round?
        $e = "(";
        $e .= "{$prd->time_column_name} >= $start_ts";
        if (!is_null($end_ts))
        {
            $e .= " AND {$prd->time_column_name} < $end_ts";
        }
        $e .= " AND state='{$prd->page_save_state}'";
        $e .= ")";

        // The project has completed all preceding rounds.
        $latest_completed_round = $prd->round_number - 1;
    }
    else
    {
        // The project has completed all page-editing rounds.
        $e = "0";
        $latest_completed_round = MAX_NUM_PAGE_EDITING_ROUNDS;
    }

    // Consider the completed rounds. For these, the only concern is
    // whether the "round time" is within the interval.
    // Do them in reverse order, just for kicks.
    for ($rn = $latest_completed_round; $rn >= 1; $rn-- )
    {
        $completed_prd = get_PRD_for_round($rn);
        $e .= " + (";
        $e .= "{$completed_prd->time_column_name} >= $start_ts";
        if (!is_null($end_ts))
        {
            $e .= " AND {$completed_prd->time_column_name} < $end_ts";
        }
        $e .= ")";
    }

    return $e;

    // The phrasing of this expression relies on the fact that
    // MySQL implements TRUE and FALSE as 1 and 0 respectively,
    // so it's meaningful to add two boolean expressions.
    // (If we were using an SQL processor that didn't do this,
    // we would have to use the IF() function or a CASE expr.)
}

// vim: sw=4 ts=4 expandtab
?>

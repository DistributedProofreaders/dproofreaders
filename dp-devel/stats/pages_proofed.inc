<?PHP
include_once($relPath.'project_states.inc');

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
        SELECT projectid
        FROM projects
        WHERE archived = '0'
            AND (state != '".PROJ_NEW."')
            AND (state != '".PROJ_SUBMIT_PG_POSTED."')
            AND (state != '".PROJ_POST_SECOND_AVAILABLE."')
            AND (state != '".PROJ_POST_SECOND_CHECKED_OUT."')
            AND (state != '".PROJ_POST_FIRST_CHECKED_OUT."'          OR modifieddate > $start_ts)
            AND (state != '".PROJ_PROOF_FIRST_WAITING_FOR_RELEASE."' OR modifieddate > $start_ts)
            AND (state != '".PROJ_POST_FIRST_UNAVAILABLE."'          OR modifieddate > $start_ts)
    ")
        or die(mysql_error());

    $n_projects = mysql_num_rows($res);

    while ( $project_row = mysql_fetch_array($res) )
    {
        list($projectid) = $project_row;

        // page finished in R1 may have since progressed to R2

        $end_constraint1 = (is_null($end_ts) ? "" : "AND round1_time < $end_ts");
        $result1 = mysql_query("
            SELECT COUNT(*)
            FROM $projectid
            WHERE
                (state='save_first' OR state LIKE '%_second%')
                AND round1_time >= $start_ts
                $end_constraint1
        ");

        if (!$result1)
        {
            // Probably the project's page-table does not exist.
            // Not sure why.
            $pages1 = 0;
            continue;
        } else {
            $row1 = mysql_fetch_row($result1);
            $pages1 = $row1[0];
        }


        $end_constraint2 = (is_null($end_ts) ? "" : "AND round2_time < $end_ts");
        $result2 = mysql_query("
            SELECT COUNT(*)
            FROM $projectid
            WHERE
                state='save_second'
                AND round2_time >= $start_ts
                $end_constraint2
        ");

        if (!$result2)
        {
            // Probably the project's page-table does not exist.
            // Not sure why.
            $pages2 = 0;
            continue;
        } else {
            $row2 = mysql_fetch_row($result2);
            $pages2 = $row2[0];
        }

        $n_pages_proofed = $pages1 + $pages2;

        mysql_free_result($result1);
        mysql_free_result($result2);

        $total_n_pages_proofed += $n_pages_proofed;
    }

    return $total_n_pages_proofed;
}
// vim: sw=4 ts=4 expandtab
?>

<?PHP
include_once($relPath.'page_states.inc');
include_once($relPath.'f_project_states.inc');

// -----------------------------------------------------------------------------

function Project_getNumAvailablePagesInRound( $projectid, $proofstate )
// Return the number of pages in the given project that are available
// for proofing in round X, where X is implied by $proofstate.
// (Usually, $proofstate is the same as the project's state,
// but the result is still well-defined if it isn't. E.g.,
// if the project is in second round, the number of pages that
// are available for first-round proofing is zero.)
{
    $project_phase = get_phase_containing_project_state($proofstate);

    if ( $project_phase == 'NEW' )
    {
        $avail_state = AVAIL_FIRST;
    }
    else if ( $project_phase == 'PAGE_EDITING' )
    {
        $prd = get_PRD_for_project_state($proofstate);
        $avail_state = $prd->page_avail_state;
    }
    else
    {
        return 0;
    }

    return Project_getNumPagesInState( $projectid, $avail_state );
}

function Project_getNumPagesInState( $projectid, $page_state )
{
    $res = mysql_query("SELECT COUNT(*) FROM $projectid WHERE state='$page_state'");
    if (!$res)
    {
        echo mysql_error();
        return 0;
    }

    list($num_pages) = mysql_fetch_row($res);

    return $num_pages;
}

function Project_getNumPages( $projectid )
{
    $res = mysql_query("SELECT COUNT(*) FROM $projectid");
    if (!$res)
    {
        echo mysql_error();
        return 0;
    }

    list($num_pages) = mysql_fetch_row($res);

    return $num_pages;
}

// vim: sw=4 ts=4 expandtab
?>

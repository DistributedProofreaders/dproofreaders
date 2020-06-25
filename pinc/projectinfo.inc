<?php
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');

// -----------------------------------------------------------------------------

function Project_getNumPagesInState( $projectid, $page_state , $counter = "*")
{
    $res = mysqli_query(DPDatabase::get_connection(), "SELECT COUNT($counter) FROM $projectid WHERE state='$page_state'");
    if (!$res)
    {
        echo DPDatabase::log_error();
        return 0;
    }

    list($num_pages) = mysqli_fetch_row($res);

    return $num_pages;
}

function Project_getNumPages( $projectid )
{
    $res = mysqli_query(DPDatabase::get_connection(), "SELECT COUNT(*) FROM $projectid");
    if (!$res)
    {
        echo DPDatabase::log_error();
        return 0;
    }

    list($num_pages) = mysqli_fetch_row($res);

    return $num_pages;
}

// vim: sw=4 ts=4 expandtab
?>

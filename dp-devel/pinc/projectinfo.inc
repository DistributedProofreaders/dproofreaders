<?PHP
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');

// -----------------------------------------------------------------------------

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

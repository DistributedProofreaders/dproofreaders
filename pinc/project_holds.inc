<?php

include_once($relPath.'project_events.inc'); // log_project_event
include_once($relPath.'misc.inc'); // surround_and_join

function remove_holds($projectid, $states)
{
    global $pguser;

    $states_str = surround_and_join( $states, "'", "'", ", " );
    $sql = "
        DELETE FROM project_holds
        WHERE projectid='$projectid'
            AND state in ($states_str)
    ";
    try_query($sql);
    log_project_event( $projectid, $pguser, 'remove_holds', join($states, ' ') );
}

function add_holds($projectid, $states)
{
    global $pguser;

    $values = '';
    foreach ($states as $state)
    {
        if ($values) $values .= ",\n";
        $values .= "('$projectid', '$state')";
    }
    $sql = "
        INSERT INTO project_holds
        VALUES $values
    ";
    try_query($sql);
    log_project_event( $projectid, $pguser, 'add_holds', join($states, ' ') );
}

function try_query($sql)
{
    $res = mysqli_query(DPDatabase::get_connection(), $sql);
    if(!$res)
    {
        throw new Exception("mysql query:\n$sql\nraised error:\n" . mysqli_error(DPDatabase::get_connection()));
    }
    return $res;
}

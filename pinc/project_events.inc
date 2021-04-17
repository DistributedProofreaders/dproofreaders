<?php

function log_project_event($projectid, $who, $event_type, $details1 = '', $details2 = '', $details3 = '')
{
    $esc_details1 = mysqli_real_escape_string(DPDatabase::get_connection(), $details1);
    $esc_details2 = mysqli_real_escape_string(DPDatabase::get_connection(), $details2);
    $esc_details3 = mysqli_real_escape_string(DPDatabase::get_connection(), $details3);

    $res = mysqli_query(DPDatabase::get_connection(), "
        INSERT INTO project_events
        SET
            timestamp  = UNIX_TIMESTAMP(),
            projectid  = '$projectid',
            who        = '$who',
            event_type = '$event_type',
            details1   = '$esc_details1',
            details2   = '$esc_details2',
            details3   = '$esc_details3'
    ");
    if (!$res) {
        return DPDatabase::log_error();
    } else {
        return '';
    }
}

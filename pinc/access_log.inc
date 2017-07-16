<?php

function log_access_change( $subject_username, $modifier_username, $activity_id, $action_type )
{
    $sql = sprintf("
        INSERT INTO access_log
        SET
            timestamp = '%s',
            subject_username = '%s',
            modifier_username = '%s',
            action = '%s',
            activity = '%s'
    ", time(),
        mysqli_real_escape_string(DPDatabase::get_connection(), $subject_username),
        mysqli_real_escape_string(DPDatabase::get_connection(), $modifier_username),
        mysqli_real_escape_string(DPDatabase::get_connection(), $action_type),
        mysqli_real_escape_string(DPDatabase::get_connection(), $activity_id));

    return mysqli_query(DPDatabase::get_connection(), $sql);
}

function get_first_granted_date($username, $stage)
{
    $sql = sprintf("
        SELECT timestamp
        FROM access_log
        WHERE subject_username = '%s'
            AND activity = '%s'
            AND action = 'grant'
        ORDER BY timestamp ASC
        LIMIT 1
    ", mysqli_real_escape_string(DPDatabase::get_connection(), $username),
        mysqli_real_escape_string(DPDatabase::get_connection(), $stage));

    $result = mysqli_query(DPDatabase::get_connection(), $sql);

    $row = mysqli_fetch_assoc($result);

    return $row["timestamp"];
}

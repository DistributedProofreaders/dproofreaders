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
        mysql_real_escape_string($subject_username),
        mysql_real_escape_string($modifier_username),
        mysql_real_escape_string($action_type),
        mysql_real_escape_string($activity_id));

    return mysql_query($sql);
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
    ", mysql_real_escape_string($username),
        mysql_real_escape_string($stage));

    $result = mysql_query($sql);

    $row = mysql_fetch_assoc($result);

    return $row["timestamp"];
}

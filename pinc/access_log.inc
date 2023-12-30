<?php

function log_access_change($subject_username, $modifier_username, $activity_id, $action_type)
{
    $setters = join(", ", [
        set_col_num("timestamp", time()),
        set_col_str("subject_username", $subject_username),
        set_col_str("modifier_username", $modifier_username),
        set_col_str("action", $action_type),
        set_col_str("activity", $activity_id),
    ]);
    $sql = "
        INSERT INTO access_log
        SET $setters
    ";
    return DPDatabase::query($sql);
}

function get_first_granted_date($username, $stage)
{
    $sql = sprintf(
        "
        SELECT timestamp
        FROM access_log
        WHERE subject_username = '%s'
            AND activity = '%s'
            AND action = 'grant'
        ORDER BY timestamp ASC
        LIMIT 1
        ",
        DPDatabase::escape($username),
        DPDatabase::escape($stage)
    );
    $result = DPDatabase::query($sql);

    $row = mysqli_fetch_assoc($result);

    if ($row) {
        return $row["timestamp"];
    } else {
        return null;
    }
}

function get_latest_access_change_entry($username, $activity_id)
{
    $sql = sprintf(
        "
        SELECT *
        FROM access_log
        WHERE subject_username = '%s'
            AND activity = '%s'
        ORDER BY timestamp DESC
        LIMIT 1
        ",
        DPDatabase::escape($username),
        DPDatabase::escape($activity_id)
    );
    $result = DPDatabase::query($sql);

    if ($result) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}

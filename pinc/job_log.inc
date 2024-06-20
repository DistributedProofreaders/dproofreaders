<?php

function insert_job_log_entry($filename, $event, $comments, $succeeded = null)
{
    $sql = sprintf(
        "
        INSERT INTO job_logs (filename, tracetime, event, comments, succeeded)
        VALUES ('%s', %d, '%s', '%s', %s)
        ",
        DPDatabase::escape($filename),
        time(),
        DPDatabase::escape($event),
        DPDatabase::escape($comments),
        $succeeded === null ? "null" : ($succeeded ? 1 : 0)
    );
    DPDatabase::query($sql);
}

function get_job_log_entries($timestamp, $filename = null, $event = null)
{
    $filename_where = $filename ? sprintf("AND filename = '%s'", DPDatabase::escape($filename)) : "";
    $event_where = $event ? sprintf("AND event = '%s'", DPDatabase::escape($event)) : "";
    $sql = sprintf(
        "
        SELECT *
        FROM job_logs
        WHERE tracetime >= %d
            $filename_where
            $event_where
        ORDER BY tracetime desc
        ",
        $timestamp
    );
    $result = DPDatabase::query($sql);
    $entries = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $entries[] = $row;
    }
    return $entries;
}

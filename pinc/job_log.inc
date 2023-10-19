<?php

function insert_job_log_entry($filename, $event, $comments, $tracetime = null)
{
    if (!$tracetime) {
        $tracetime = time();
    }

    $sql = sprintf(
        "
        INSERT INTO job_logs (filename, tracetime, event, comments)
        VALUES ('%s', %d, '%s', '%s')
    ",
        DPDatabase::escape($filename),
        $tracetime,
        DPDatabase::escape($event),
        DPDatabase::escape($comments)
    );
    DPDatabase::query($sql);
}

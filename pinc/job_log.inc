<?php

/** @return string[] */
function get_job_log_filenames(): array
{
    $sql = sprintf(
        "
        SELECT DISTINCT filename
        FROM job_logs
        WHERE tracetime >= %d
        ORDER BY filename
        ",
        time() - (60 * 60 * 24 * 45), // 45 days covers all common jobs
    );
    $result = DPDatabase::query($sql);
    $entries = [];
    while ([$filename] = mysqli_fetch_row($result)) {
        $entries[] = $filename;
    }
    return $entries;
}

function insert_job_log_entry(string $filename, string $event, ?string $comments, ?bool $succeeded = null): void
{
    $sql = sprintf(
        "
        INSERT INTO job_logs (filename, tracetime, event, comments, succeeded)
        VALUES ('%s', %d, '%s', '%s', %s)
        ",
        DPDatabase::escape($filename),
        time(),
        DPDatabase::escape($event),
        substr(DPDatabase::escape($comments), 0, 255),
        $succeeded === null ? "null" : ($succeeded ? 1 : 0)
    );
    DPDatabase::query($sql);
}

function get_job_log_entries(int $timestamp, ?string $filename = null, ?string $event = null, ?bool $succeeded = null): array
{
    $filename_where = $filename ? sprintf("AND filename = '%s'", DPDatabase::escape($filename)) : "";
    $event_where = $event ? sprintf("AND event = '%s'", DPDatabase::escape($event)) : "";
    $succeeded_where = $succeeded !== null ? sprintf("AND succeeded = %d", $succeeded ? 1 : 0) : "";
    $sql = sprintf(
        "
        SELECT *
        FROM job_logs
        WHERE tracetime >= %d
            $filename_where
            $event_where
            $succeeded_where
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

function prune_job_log_entries(int $days_to_keep): void
{
    $cutoff = new DateTimeImmutable(sprintf("%d days ago", $days_to_keep));
    $sql = sprintf(
        "
        DELETE
        FROM job_logs
        WHERE tracetime < %d
        ",
        $cutoff->format("U")
    );
    DPDatabase::query($sql);
}

function count_job_log_failures(int $days_ago): int
{
    $cutoff = new DateTimeImmutable(sprintf("%d days ago", $days_ago));
    $sql = sprintf(
        "
        SELECT count(*)
        FROM job_logs
        WHERE tracetime > %d AND succeeded = 0
        ",
        $cutoff->format("U")
    );
    $result = DPDatabase::query($sql);
    [$count] = mysqli_fetch_row($result);
    return $count;
}

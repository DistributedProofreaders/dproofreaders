<?php
include_once($relPath."job_log.inc");

// Prune job_log entries
class PruneJobLogs extends BackgroundJob
{
    public function work()
    {
        prune_job_log_entries(SiteConfig::get()->days_to_retain_job_logs);
    }
}

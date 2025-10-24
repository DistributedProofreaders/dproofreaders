<?php

// Find projects that were posted to PG a while ago and prune page data.
class PrunePageData extends BackgroundJob
{
    public function work()
    {
        $database = SiteConfig::get()->archive_db_name ? SiteConfig::get()->archive_db_name : SiteConfig::get()->db_name;

        // We need to iteratively delete data in batches by projectid because:
        // 1. we base the pruning on the project's posted time
        // 2. there are indexes for these tables based on projectid
        // 3. to possibly limit our runtime
        $sql = sprintf(
            "
            SELECT projectid
            FROM projects
            WHERE
                modifieddate <= UNIX_TIMESTAMP() - (24 * 60 * 60) * %d
                AND state = '%s'
            ORDER BY modifieddate
            ",
            SiteConfig::get()->days_to_retain_page_data_after_posting,
            DPDatabase::escape(PROJ_SUBMIT_PG_POSTED)
        );
        $result = DPDatabase::query($sql);
        $num_projects = mysqli_num_rows($result);

        echo "Pruning data for $num_projects projects...\n";

        $num_projects_pruned = 0;
        while ([$project_id] = mysqli_fetch_row($result)) {
            if ($this->watch->read() >= $this->web_context_max_runtime_s) {
                break;
            }

            // first wordcheck_events
            $sql = sprintf(
                "
                DELETE FROM $database.wordcheck_events
                WHERE projectid = '%s'
                ",
                DPDatabase::escape($project_id)
            );
            DPDatabase::query($sql);

            // then page_events
            $sql = sprintf(
                "
                DELETE FROM $database.page_events
                WHERE projectid = '%s'
                ",
                DPDatabase::escape($project_id)
            );
            DPDatabase::query($sql);

            $num_projects_pruned += 1;
        }

        $leftover_projects = $num_projects - $num_projects_pruned;
        if ($leftover_projects) {
            echo "Reached runtime limit, skipping pruning of page data for remaining $leftover_projects projects.\n";
            $this->stop_message = "Pruned page data for $num_projects_pruned projects, ran out of time to prune page data for remaining $leftover_projects";
        } else {
            $this->stop_message = "Pruned page data for $num_projects_pruned projects";
        }
    }
}

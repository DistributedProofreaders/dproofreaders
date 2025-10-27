<?php

// Find projects that were posted to PG a while ago and prune page data.
class PrunePageData extends BackgroundJob
{
    public function work()
    {
        $database = SiteConfig::get()->archive_db_name ? SiteConfig::get()->archive_db_name : SiteConfig::get()->db_name;

        // See if there's a milestone marker in memcache for where we left off
        $memcache = new Memcached();
        $memcache->addServer('localhost', 11211);
        $last_modifieddate = $memcache->get("PrunePageData:last_modifieddate");
        if ($last_modifieddate !== false and is_numeric($last_modifieddate)) {
            echo "Starting pruning at milestone $last_modifieddate\n";
        } else {
            echo "No last milestone found, starting at the beginning\n";
            $last_modifieddate = 0;
        }

        // We need to iteratively delete data in batches by projectid because:
        // 1. we base the pruning on the project's posted time
        // 2. there are indexes for these tables based on projectid
        // 3. to possibly limit our runtime
        $sql = sprintf(
            "
            SELECT projectid, modifieddate
            FROM projects
            WHERE
                modifieddate <= UNIX_TIMESTAMP() - (24 * 60 * 60) * %d
                AND modifieddate > %d
                AND state = '%s'
            ORDER BY modifieddate
            ",
            SiteConfig::get()->days_to_retain_page_data_after_posting,
            $last_modifieddate,
            DPDatabase::escape(PROJ_SUBMIT_PG_POSTED)
        );
        $result = DPDatabase::query($sql);
        $num_projects = mysqli_num_rows($result);

        echo "Pruning page data for $num_projects projects...\n";

        $num_projects_pruned = 0;
        while ([$project_id, $modifieddate] = mysqli_fetch_row($result)) {
            if ($this->watch->read() >= $this->web_context_max_runtime_s) {
                break;
            }

            echo "Pruning page data for $project_id ($modifieddate)...\n";

            // first wordcheck_events
            $sql = sprintf(
                "
                DELETE FROM $database.wordcheck_events
                WHERE projectid = '%s'
                ",
                DPDatabase::escape($project_id)
            );
            DPDatabase::query($sql);

            // then format_preview_events
            $sql = sprintf(
                "
                DELETE FROM $database.format_preview_events
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

            $last_modifieddate = $modifieddate;
        }

        if ($memcache->set("PrunePageData:last_modifieddate", $last_modifieddate) !== false) {
            echo "Set last milestone to $last_modifieddate\n";
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

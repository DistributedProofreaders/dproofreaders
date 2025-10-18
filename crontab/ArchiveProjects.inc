<?php
include_once($relPath.'archiving.inc');

// Find projects that were posted to PG a while ago and archive them.
class ArchiveProjects extends BackgroundJob
{
    public bool $requires_web_context = true;

    public function work()
    {
        $sql = sprintf(
            "
            SELECT *
            FROM projects
            WHERE
                modifieddate <= UNIX_TIMESTAMP() - (24 * 60 * 60) * %d
                AND archived = '0'
                AND state = '%s'
            ORDER BY modifieddate
            ",
            SiteConfig::get()->days_to_retain_projects_before_archiving,
            DPDatabase::escape(PROJ_SUBMIT_PG_POSTED)
        );
        $result = DPDatabase::query($sql);
        $num_projects = mysqli_num_rows($result);

        echo "Archiving page-tables for $num_projects projects...\n";

        $num_projects_archived = 0;
        while ($project_data = mysqli_fetch_assoc($result)) {
            if ($this->watch->read() >= $this->web_context_max_runtime_s) {
                break;
            }

            $project = new Project($project_data);
            archive_project($project);
            $num_projects_archived += 1;
        }

        $leftover_projects = $num_projects - $num_projects_archived;
        if ($leftover_projects) {
            echo "Reached runtime limit, skipping archive of remaining $leftover_projects projects.\n";
            $this->stop_message = "Archived $num_projects_archived projects, ran out of time to archive remaining $leftover_projects";
        } else {
            $this->stop_message = "Archived $num_projects_archived projects";
        }
    }
}

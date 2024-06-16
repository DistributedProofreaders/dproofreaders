<?php
include_once($relPath.'archiving.inc');

// Find projects that were posted to PG a while ago and archive them.
class ArchiveProjects extends BackgroundJob
{
    private int $days_to_retain = 100;
    public bool $requires_web_context = true;

    public function work()
    {
        $sql = sprintf(
            "
            SELECT *
            FROM projects
            WHERE
                modifieddate <= UNIX_TIMESTAMP() - (24 * 60 * 60) * {$this->days_to_retain}
                AND archived = '0'
                AND state = '%s'
            ORDER BY modifieddate
            ",
            DPDatabase::escape(PROJ_SUBMIT_PG_POSTED)
        );
        $result = DPDatabase::query($sql);
        $num_projects = mysqli_num_rows($result);

        echo "Archiving page-tables for $num_projects projects...\n";

        while ($project_data = mysqli_fetch_assoc($result)) {
            $project = new Project($project_data);
            archive_project($project);
        }

        $this->stop_message = "Archived $num_projects projects";
    }
}

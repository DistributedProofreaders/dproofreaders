<?php
include_once($relPath.'user_project_info.inc');
include_once($relPath.'Project.inc');

// Timing requirements: This script should run at 5 minutes past each hour

// Send SR finish notifications
class SendSmoothreadingNotifications extends BackgroundJob
{
    public function work()
    {
        // Assume smooth read deadlines are at exact hours
        // Select all projects whose smooth reading deadline is within the past hour.
        $from = -60 * 60;
        $sql = sprintf(
            "
            SELECT *
            FROM projects
            WHERE
                smoothread_deadline >= (UNIX_TIMESTAMP() + %d)
                AND smoothread_deadline <= UNIX_TIMESTAMP()
                AND state = '%s'
            ",
            $from,
            DPDatabase::escape(PROJ_POST_FIRST_CHECKED_OUT)
        );
        $result = DPDatabase::query($sql);

        $number_of_projects = mysqli_num_rows($result);

        if ($number_of_projects > 0) {
            echo "Found $number_of_projects projects...\n";
            while ($row = mysqli_fetch_assoc($result)) {
                $project = new Project($row);
                echo "$project->nameofwork\n";
                echo "    Sending email and logging event...\n";
                $project->log_project_event('[AUTO]', 'smooth-reading', 'finished');
                notify_project_event_subscribers($project, 'sr_complete');
            }
            $this->stop_message = "Sent notifications for $number_of_projects";
        } else {
            $this->stop_message = "No notifications sent";
        }
    }
}

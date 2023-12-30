<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'user_project_info.inc');
include_once($relPath.'Project.inc');

require_localhost_request();

header('Content-type: text/plain');

$dry_run = array_get($_GET, 'dry_run', '');
if ($dry_run) {
    echo "This is a dry run.\n";
}

// Interval relative to current time
//   Select all projects whose smooth reading deadline is within this interval.
$from = -60 * 60;
$to = 60 * 60;

$sql = sprintf(
    "
    SELECT *
    FROM projects
    WHERE
        smoothread_deadline >= (UNIX_TIMESTAMP() + %d)
        AND smoothread_deadline <= (UNIX_TIMESTAMP() + %d)
        AND state = '%s'
    ",
    $from,
    $to,
    DPDatabase::escape(PROJ_POST_FIRST_CHECKED_OUT)
);
$result = DPDatabase::query($sql);

$output = "Checking " . mysqli_num_rows($result) . " projects...\n";
$any_work_done = false;

// Used for checking smoothread_deadline within the loop
$curr_time = date('Y-m-d H');

while ($row = mysqli_fetch_assoc($result)) {
    $project = new Project($row);

    // Check if the time is right, with precision of an hour
    $deadline = date('Y-m-d H', $project->smoothread_deadline);

    if ($curr_time == $deadline) {
        $output .= "$project->nameofwork\n";
        $any_work_done = true;

        if ($dry_run) {
            $output .= "  Since this is a dry run, we won't send an email and log an event\n";
        } else {
            $output .= "  Sending email and logging event...\n";
            $project->log_project_event('[AUTO]', 'smooth-reading', 'finished');
            notify_project_event_subscribers($project, 'sr_complete');
        }
    }
}

$output .= "finish_smoothreading.php executed.\n";

// Don't output anything if no work was done
if ($any_work_done) {
    echo $output;
}

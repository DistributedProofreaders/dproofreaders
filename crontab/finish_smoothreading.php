<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_events.inc');
include_once($relPath.'user_project_info.inc');
include_once($relPath.'Project.inc');

require_localhost_request();

header('Content-type: text/plain');

$dry_run = array_get( $_GET, 'dry_run', '' );
if ($dry_run)
{
    echo "This is a dry run.\n";
}

// Interval relative to current time
//   Select all projects whose smooth reading deadline is within this interval.
$from =  -60 * 60;
$to   =   60 * 60;

$result = mysqli_query(DPDatabase::get_connection(), "
    SELECT *
    FROM projects
    WHERE
        smoothread_deadline >= (UNIX_TIMESTAMP() + $from) 
        AND smoothread_deadline <= (UNIX_TIMESTAMP() + $to) 
        AND state = '".PROJ_POST_FIRST_CHECKED_OUT."'
") or die(DPDatabase::log_error());

$output = "Checking " . mysqli_num_rows($result) . " projects...\n";
$any_work_done = false;

// Used for checking smoothread_deadline within the loop
$curr_time = strftime('%Y-%m-%d %H', time());

while ( $project = mysqli_fetch_assoc($result) )
{
    $name = $project['nameofwork'];
    $projectid = $project['projectid'];
    $objProject = new Project($project);

    // Check if the time is right, with precision of an hour
    $deadline = strftime('%Y-%m-%d %H', $project['smoothread_deadline']);

    if ($curr_time == $deadline)
    {
        $output .= "$name\n";
        $any_work_done = true;

        if ($dry_run)
        {
            $output .= "  Since this is a dry run, we won't send an email and log an event\n";
        }
        else
        {
            $output .= "  Sending email and logging event...\n";
            log_project_event( $projectid, '[AUTO]', 'smooth-reading', 'finished' );
            notify_project_event_subscribers( $objProject, 'sr_complete' );
        }
    }
}

$output .= "finish_smoothreading.php executed.\n";

// Don't output anything if no work was done
if ($any_work_done)
{
    echo $output;
}


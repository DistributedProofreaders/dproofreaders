<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'misc.inc');

require_localhost_request();

header('Content-type: text/plain');

$testing_this_script=@$_GET['testing'];


// See if this has been run once today or not
$date_string = date('Y-m-d');
$sql = "
    SELECT count(*) AS count
    FROM project_state_stats
    WHERE date = '$date_string'
";
$res = mysqli_query(DPDatabase::get_connection(), $sql)
    or die(DPDatabase::log_error());
$row = mysqli_fetch_assoc($res);
if($row["count"])
{
    echo "Already run once for today ($date_string), exiting.\n";
    exit;
}


// The SELECT we do below will only return counts for project-states that are
// currently occupied. (I.e., there's at least one project in that state.)
// But we want project_state_stats to get a row for every possible state for
// every day, so we must do something extra to record zero for the unoccupied
// states.

// Initialize $num_projects_in_state_ and $num_pages_in_state_
// to zero for every currently-defined state.
$num_projects_in_state_ = array();
$num_pages_in_state_ = array();
foreach ( $PROJECT_STATES_IN_ORDER as $state )
{
    $num_projects_in_state_[$state] = 0;
    $num_pages_in_state_[$state] = 0;
}

// Get the number of projects in each (currently-occupied) state.
$sql = "
    SELECT state, count(*), sum(n_pages)
    FROM projects
    GROUP BY state
    ORDER BY state
";
$result = mysqli_query(DPDatabase::get_connection(), $sql);

while (list ($state, $num_projects, $num_pages) = mysqli_fetch_row($result)) {
    $num_projects_in_state_[$state] = $num_projects;
    $num_pages_in_state_[$state] = $num_pages;
}

// $num_projects_in_state_ now has an entry for every defined state and for
// every occupied state. (The occupied states should be a subset of the defined
// states, but you never know.)
// Insert a row into project_state_stats for each of those entries.

foreach ( array_keys($num_projects_in_state_) as $state )
{
    $num_projects = $num_projects_in_state_[$state];
    $num_pages = $num_pages_in_state_[$state];

    $nprojs = sprintf( "%5d", $num_projects );
    $npages = sprintf( "%7d", $num_pages );

    $insert_query = "
        INSERT INTO project_state_stats
        SET date=NOW(), state='$state',
            num_projects=$num_projects, num_pages=$num_pages
    ";

    if ($testing_this_script)
    {
        echo "$insert_query\n";
    }
    else
    {
        mysqli_query(DPDatabase::get_connection(), $insert_query)
            or die(DPDatabase::log_error());
    }
}

// vim: sw=4 ts=4 expandtab

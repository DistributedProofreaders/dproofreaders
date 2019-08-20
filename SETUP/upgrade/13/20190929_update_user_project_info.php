<?php

$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include($relPath.'udb_user.php'); // $archive_db_name

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Updating user_project_info to set t_latest_page_event=1 only for\n";
echo "projects where the user has saved a page as done or in-progress...\n\n";

// Load a list of all users
$sql = "
    SELECT username
    FROM users
    ORDER BY username
";

$result = mysqli_query(DPDatabase::get_connection(), $sql)
    or die( mysqli_error(DPDatabase::get_connection()) );

$users = [];
while($row = mysqli_fetch_assoc($result))
{
    $users[] = $row['username'];
}
mysqli_free_result($result);

// Loop though all users getting their user_project_info records to see what
// projects they have "worked on" (ie: t_latest_page_event > 0)
foreach($users as $username)
{
    echo "Processing $username...\n";

    // load all projects this user has "worked on"
    $sql = "
        SELECT projectid
        FROM user_project_info
        WHERE
            username='$username'
            AND t_latest_page_event > 0
    ";

    $result = mysqli_query(DPDatabase::get_connection(), $sql)
        or die( mysqli_error(DPDatabase::get_connection()) );

    $projectids = [];
    while($row = mysqli_fetch_assoc($result))
    {
        $projectids[] = $row['projectid'];
    }
    mysqli_free_result($result);

    // For each project, get the distinct event_types from page_events, which
    // indicates what the user did in that project. If neither the 'saveAsDone'
    // or the 'saveAsInProgress' events are present then this is a case where
    // the new code in pinc/DPage.inc would not have updated t_latest_page_event.
    // For these projects, set user_project_info.t_latest_page_event=0 for this
    // (username, projectid), to achieve the same effect retroactively.
    foreach($projectids as $projectid)
    {
        // Process both the main page_events table and the archived
        // page_events table.
        $current_event_types = get_distinct_event_types("page_events", $username, $projectid);
        $archived_event_types = get_distinct_event_types("$archive_db_name.page_events", $username, $projectid);
        $event_types = array_unique(array_merge($current_event_types, $archived_event_types));

        // see if the only event_types are checkout and returnToRound
        if(in_array('saveAsDone', $event_types) ||
            in_array('saveAsInProgress', $event_types))
        {
            continue;
        }
        else
        {
            echo "    setting t_latest_page_event=0 for project $projectid\n";
            $sql = "
                UPDATE user_project_info
                SET t_latest_page_event=0
                WHERE
                    username='$username'
                    AND projectid='$projectid';
            ";
            $result = mysqli_query(DPDatabase::get_connection(), $sql)
                or die( mysqli_error(DPDatabase::get_connection()) );
        }
    }
}

// ------------------------------------------------------------

echo "\nDone!\n";

// ------------------------------------------------------------

function get_distinct_event_types($table, $username, $projectid)
{
    $sql = "
        SELECT DISTINCT event_type
        FROM $table
        WHERE
            username='$username'
            AND projectid='$projectid'
    ";
    $result = mysqli_query(DPDatabase::get_connection(), $sql)
        or die( mysqli_error(DPDatabase::get_connection()) );

    $event_types = [];
    while($row = mysqli_fetch_assoc($result))
    {
        $event_types[] = $row['event_type'];
    }
    mysqli_free_result($result);
    return $event_types;
}

// vim: sw=4 ts=4 expandtab

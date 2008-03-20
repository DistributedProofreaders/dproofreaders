#!/usr/bin/php
<?php

$dry_run = FALSE;

// The number of seconds to wait between adding an index.
// This can enable the script to be used while the site
// is still up and accessible. Note this number can be
// a float (ie: 0.5 for half a second).
$delay = 0;

// If the script had to be canceled and restarted for some
// reason you can 'skip' to a specific project number in
// the sequence (they are ordered by projectid so the
// sequence should stay the same) by setting this to the
// last sequence number reported before it was canceled.
// Note that the script will handle the case (via messages
// at the end of the run) of projectID* tables already
// having the desired indexes so this is mostly a safety
// net, but it's here if you need/want it.
$start_with_sequence_number = 0;

$relPath='../../../pinc/';
include_once($relPath.'stages.inc');
include_once($relPath.'dpsql.inc');
new dbConnect();

echo "Adding round#_user indexes to all projectID* tables...\n";

// First, obtain the names of username fields in the rounds
$user_column_names = array();
foreach ( $Round_for_round_id_ as $id => $round )
{
    $user_column_names[] = $round->user_column_name;
}

// Find all project IDs
$res = dpsql_query("
    SELECT projectid
    FROM projects
    ORDER BY projectid
");

$n_projects = mysql_num_rows($res);

$messages = array();

$delay_microseconds = ceil($delay * 1000000);

// Loop through each project
$project_index = 0;
while ( list($projectid) = mysql_fetch_row($res) )
{
    // print out a status message
    $project_index++;

    // check that we're past our desired start sequence number
    if ($project_index < $start_with_sequence_number )
        continue;

    echo sprintf( "%5d/%5d: %s:", $project_index, $n_projects, $projectid );

    // confirm the projectID* table exists (ie: not archived or deleted)
    $describe_results = mysql_query("SELECT 1 FROM $projectid LIMIT 0");
    if (!$describe_results)
    {
        echo " skipping, project table does not exist.\n";
        continue;
    }
    mysql_free_result($describe_results);

    // projectID* table exists, add indexes one at a time,
    // possibly with a delay ($delay seconds)
    $round_counter = 1;
    foreach( $user_column_names as $column )
    {
        $sql = "
            ALTER TABLE $projectid
                ADD INDEX $column ( $column )
        ";

        // print out an indicator of which round we're on
        // for this project
        echo " [$round_counter";

        if($dry_run)
            echo $sql;
        else
        {
            $result = mysql_query($sql);
            if(!$result)
            {
                $error = mysql_error();
                // stash the messages away for later
                $messages[] = "$projectid: $error";
                // and print it out now to allow detecting errors early on
                echo " Error: $error";
            }
        }

        if($delay)
        {
            echo " (sleeping: $delay)";
            usleep($delay_microseconds);
        }
        echo "]";
        $round_counter++;
    }
    echo "\n";
}

// print out any messages collected along the way
if(count($messages))
{
    echo "The following messages were collected during processing:\n";
    echo "Note: Messages reading \"Duplicate key name 'round#_user'\" can be safely ignored\n";
    echo "      as that merely indicates the desired index already existed on the table.\n";
    foreach($messages as $message)
        printf("    %s\n", $message);
}

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>

<?php

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ---------------------------------------------------

echo "Adding t_last_change_comments...\n";
$sql = "
    ALTER TABLE projects
        ADD COLUMN t_last_change_comments INT NOT NULL
            AFTER t_last_edit
";
echo "$sql\n";
mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "... and initializing it...\n";
$sql = "
    UPDATE projects
    SET t_last_change_comments = t_last_edit
";
echo "$sql\n";
mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ---------------------------------------------------

echo "Adding t_last_page_done...\n";
$sql = "
    ALTER TABLE projects
        ADD COLUMN t_last_page_done INT NOT NULL
            AFTER t_last_change_comments
";
echo "$sql\n";
mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "... and initializing it...\n";
// Note that this ignores projects whose last save-as-done predates page_events.
// I'm working on that.
$sql = "
    SELECT projectid, MAX(timestamp)
    FROM page_events
    WHERE event_type='saveAsDone'
    GROUP BY projectid
";
$res = mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );
$n = mysqli_num_rows($res);
echo "for $n projects...\n";
while ( list($projectid,$max_timestamp) = mysqli_fetch_row($res) )
{
    // echo "$projectid\n";
    $sql = "
        UPDATE projects
        SET t_last_page_done = $max_timestamp
        WHERE projectid = '$projectid'
    ";
    mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );
}

// ---------------------------------------------------

echo "Adding an index for projectid,archived,state...\n";
$sql = "
    ALTER TABLE `projects`
        ADD INDEX `projectid_archived_state`
            ( `projectid`, `archived`, `state` );
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ---------------------------------------------------

echo "Replacing 'state' index with 'state,modifieddate' index...\n";
echo "Adding an index for state,modifieddate index ...\n";
$sql = "
    ALTER TABLE `projects`
        ADD INDEX `state_moddate`
            ( `state`, `modifieddate` );
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "Dropping 'state' index ...\n";
$sql = "
    ALTER TABLE `projects`
        DROP INDEX `state`;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ---------------------------------------------------

echo "Dropping obsolete columns: txtlink_obsolete, ziplink_obsolete, htmllink_obsolete...\n";
$sql = "
    ALTER TABLE `projects`
        DROP COLUMN txtlink_obsolete,
        DROP COLUMN ziplink_obsolete,
        DROP COLUMN htmllink_obsolete;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab
?>

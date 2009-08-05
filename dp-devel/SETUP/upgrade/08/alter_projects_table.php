<?php

$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');

// ---------------------------------------------------

echo "Adding t_last_change_comments...\n";
$sql = "
    ALTER TABLE projects
        ADD COLUMN t_last_change_comments INT NOT NULL
            AFTER t_last_edit
";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

echo "... and initializing it...\n";
$sql = "
    UPDATE projects
    SET t_last_change_comments = t_last_edit
";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

// ---------------------------------------------------

echo "Adding t_last_page_done...\n";
$sql = "
    ALTER TABLE projects
        ADD COLUMN t_last_page_done INT NOT NULL
            AFTER t_last_change_comments
";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

echo "... and initializing it...\n";
// Note that this ignores projects whose last save-as-done predates page_events.
// I'm working on that.
$sql = "
    SELECT projectid, MAX(timestamp)
    FROM page_events
    WHERE event_type='saveAsDone'
    GROUP BY projectid
";
$res = mysql_query($sql) or die( mysql_error() );
$n = mysql_num_rows($res);
echo "for $n projects...\n";
while ( list($projectid,$max_timestamp) = mysql_fetch_row($res) )
{
    // echo "$projectid\n";
    $sql = "
        UPDATE projects
        SET t_last_page_done = $max_timestamp
        WHERE projectid = '$projectid'
    ";
    mysql_query($sql) or die( mysql_error() );
}

// ---------------------------------------------------

echo "Adding an index for projectid,archived,state...\n";
$sql = "
    ALTER TABLE `projects`
        ADD INDEX `projectid_archived_state`
            ( `projectid`, `archived`, `state` );
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

// ---------------------------------------------------

echo "Replacing 'state' index with 'state,modifieddate' index...\n";
echo "Adding an index for state,modifieddate index ...\n";
$sql = "
    ALTER TABLE `projects`
        ADD INDEX `state_moddate`
            ( `state`, `modifieddate` );
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

echo "Dropping 'state' index ...\n";
$sql = "
    ALTER TABLE `projects`
        DROP INDEX `state`;
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab
?>

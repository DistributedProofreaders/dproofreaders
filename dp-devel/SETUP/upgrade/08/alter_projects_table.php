<?php

$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');

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

echo "Adding an index for projectid,archived,state...\n";
$sql = "
    ALTER TABLE `projects`
        ADD INDEX `projectid_archived_state`
            ( `projectid`, `archived`, `state` );
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab
?>

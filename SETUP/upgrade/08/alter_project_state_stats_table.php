<?php

$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Adding a column to project_state_stats...\n";
$sql = "
    ALTER TABLE project_state_stats
        ADD COLUMN num_pages INT(12) NOT NULL DEFAULT 0 AFTER num_projects; 
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

// ------------------------------------------------------------

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab
?>

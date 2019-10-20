<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Updating project_state_stats.date default value..\n";
$sql = "
    ALTER TABLE project_state_stats
        MODIFY COLUMN date date NOT NULL DEFAULT '2003-01-01';
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "Dropping columns from project_state_stats..\n";
$sql = "
    ALTER TABLE project_state_stats
        DROP COLUMN day,
        DROP COLUMN month,
        DROP COLUMN year;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );


// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab

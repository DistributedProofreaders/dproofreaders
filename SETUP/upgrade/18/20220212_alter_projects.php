<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Delete correctedby column from projects table\n";
$sql = "
    ALTER TABLE projects
        DROP COLUMN correctedby
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

// ------------------------------------------------------------

echo "Altering projects table...\n";

$sql = "
    ALTER TABLE projects
        DROP COLUMN year,
        DROP COLUMN updated
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

// ------------------------------------------------------------

echo "Adding username column to sessions table...\n";

$sql = "
    ALTER TABLE sessions
        ADD COLUMN username varchar(25) NOT NULL DEFAULT ''
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

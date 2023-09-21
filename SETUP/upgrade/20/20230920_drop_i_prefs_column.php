<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Dropping users.i_prefs column\n";
$sql = "
    ALTER TABLE users
    DROP COLUMN i_prefs
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

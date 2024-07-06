<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

// ------------------------------------------------------------

echo "Altering wordcheck_events...\n";

$sql = "
    ALTER TABLE `wordcheck_events`
        DROP COLUMN `corrections`
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

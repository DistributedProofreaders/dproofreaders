<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

echo "Altering 'queue_defns' table...\n";

mysqli_query(DPDatabase::get_connection(), "
    ALTER TABLE queue_defns
        MODIFY COLUMN ordering MEDIUMINT(5) NOT NULL DEFAULT '0'
") or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";
?>

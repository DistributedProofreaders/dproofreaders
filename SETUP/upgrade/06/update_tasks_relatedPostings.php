<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Adding 'related_postings' column to 'tasks' table...\n";
$result = mysqli_query(DPDatabase::get_connection(), "ALTER TABLE `tasks` ADD `related_postings` MEDIUMTEXT NOT NULL") or die(mysqli_error(DPDatabase::get_connection()));

echo "Initializing it...\n";
$empty_array_value = base64_encode(serialize(array()));
mysqli_query(DPDatabase::get_connection(), "
    UPDATE tasks
    SET related_postings='$empty_array_value'
    WHERE related_postings=''
") or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";
?>

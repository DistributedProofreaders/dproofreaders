<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "\n";
echo "Removing bogus 'task_priority' column...\n";

mysqli_query(DPDatabase::get_connection(), "
    ALTER TABLE users
        DROP COLUMN task_priority
") or print(mysqli_error(DPDatabase::get_connection())."\n");


echo "\n";
echo "Adding 't_last_activity' column...\n";

mysqli_query(DPDatabase::get_connection(), "
    ALTER TABLE users
        ADD COLUMN t_last_activity INT UNSIGNED NOT NULL AFTER last_login,
        ADD INDEX (t_last_activity)
") or die(mysqli_error(DPDatabase::get_connection()));

echo "Initializing it to the time of last login...\n";
mysqli_query(DPDatabase::get_connection(), "
    UPDATE users
    SET t_last_activity=last_login
") or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>

<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Adding 'latestUser' column to 'user_teams' table...\n";
$result = mysqli_query(DPDatabase::get_connection(), "ALTER TABLE `user_teams` ADD `latestUser` MEDIUMINT DEFAULT 0 NOT NULL") or die(mysqli_error(DPDatabase::get_connection()));
echo "\nDone!\n";
?>

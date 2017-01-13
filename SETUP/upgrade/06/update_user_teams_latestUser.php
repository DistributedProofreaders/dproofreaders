<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Adding 'latestUser' column to 'user_teams' table...\n";
$result = mysql_query("ALTER TABLE `user_teams` ADD `latestUser` MEDIUMINT DEFAULT 0 NOT NULL") or die(mysql_error());
echo "\nDone!\n";
?>

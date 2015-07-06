<?php
$relPath='../../../pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

echo "Adding 'latestUser' column to 'user_teams' table...\n";
$result = mysql_query("ALTER TABLE `user_teams` ADD `latestUser` MEDIUMINT DEFAULT 0 NOT NULL") or die(mysql_error());
echo "\nDone!\n";
?>

<?php
$relPath='./pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

//----------------------------------------------------------------------------------------------------------------------
$result = mysql_query("ALTER TABLE `user_teams` ADD `latestUser` MEDIUMINT DEFAULT 0 NOT NULL") or die(mysql_error());
echo "<center><p>Addition of `latestUser` field to `user_teams` table complete!";
?>
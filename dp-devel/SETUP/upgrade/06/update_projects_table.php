<?php
$relPath='./pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

//----------------------------------------------------------------------------------------------------------------------
$result = mysql_query("ALTER TABLE `projects` ADD `final_pg_count` SMALLINT( 4 ) UNSIGNED DEFAULT '0' NOT NULL") or die(mysql_error());
$result = mysql_query("ALTER TABLE `projects` ADD `ppverifier` VARCHAR( 25 )") or die(mysql_error());
echo "<center><p>Addition of `final_pg_count` and `ppverifier` fields to `projects` table complete!";
?>

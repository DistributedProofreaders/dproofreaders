<?php
$relPath='./pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

//----------------------------------------------------------------------------------------------------------------------
$result = mysql_query("ALTER TABLE `projects` ADD `final_page_count` SMALLINT( 4 ) UNSIGNED DEFAULT '0' NOT NULL") or die(mysql_error());
$result = mysql_query("ALTER TABLE `projects` ADD `ppverifier` VARCHAR( 25 )") or die(mysql_error());
$result = mysql_query("ALTER TABLE `projects` ADD `special` VARCHAR( 20 ) AFTER `projectid`") or die(mysql_error());
$result = mysql_query("ALTER TABLE `projects` ADD `image_provider` VARCHAR( 10 )") or die(mysql_error());
$result = mysql_query("ALTER TABLE `projects` ADD INDEX ( `special` )") or die(mysql_error());

echo "<center><p>Addition of `final_page_count`, `special`, `ppverifier` and `image_provider` fields to `projects` table complete!";
?>

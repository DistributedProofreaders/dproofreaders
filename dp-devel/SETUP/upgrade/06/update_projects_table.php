<?php
$relPath='./pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

//----------------------------------------------------------------------------------------------------------------------
$result = mysql_query("ALTER TABLE `projects` ADD `final_page_count` SMALLINT( 4 ) UNSIGNED DEFAULT '0' NOT NULL") or die(mysql_error());
$result = mysql_query("ALTER TABLE `projects` ADD `ppverifier` VARCHAR( 25 )") or die(mysql_error());
$result = mysql_query("ALTER TABLE `projects` ADD `special` VARCHAR( 20 ) AFTER `projectid`") or die(mysql_error());
$result = mysql_query("ALTER TABLE `projects` ADD `image_provider` VARCHAR( 10 )") or die(mysql_error());
$result = mysql_query("ALTER TABLE `projects` ADD `smoothread_deadline` INT( 20 ) DEFAULT '0' NOT NULL") or die(mysql_error());
$result = mysql_query("ALTER TABLE `projects` ADD `up_projectid` INT( 10 ) DEFAULT '0'") or die(mysql_error());
$result = mysql_query("ALTER TABLE `projects` ADD INDEX ( `special` )") or die(mysql_error());

echo "<center><p>Addition of `final_page_count`, `special`, `ppverifier`, `image_provider`, `smoothread_deadline` and `up_projectid` fields to `projects` table complete!";
?>

<?php
$relPath='./pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

//----------------------------------------------------------------------------------------------------------------------
$result = mysql_query("CREATE TABLE `tasks` (
  `task_id` mediumint(9) NOT NULL auto_increment,
  `task_summary` varchar(80) NOT NULL default '',
  `task_type` tinyint(3) unsigned NOT NULL default '0',
  `task_category` tinyint(3) unsigned NOT NULL default '0',
  `task_status` tinyint(3) unsigned NOT NULL default '0',
  `task_assignee` mediumint(8) unsigned NOT NULL default '0',
  `task_severity` tinyint(3) unsigned NOT NULL default '0',
  `task_os` tinyint(3) unsigned NOT NULL default '0',
  `task_browser` tinyint(3) unsigned NOT NULL default '0',
  `task_version` tinyint(3) unsigned NOT NULL default '0',
  `task_details` mediumtext NOT NULL,
  `date_opened` int(11) NOT NULL default '0',
  `opened_by` mediumint(9) NOT NULL default '0',
  `date_closed` int(11) NOT NULL default '0',
  `closed_by` mediumint(9) NOT NULL default '0',
  `closed_reason` tinyint(4) NOT NULL default '0',
  `date_edited` int(11) NOT NULL default '0',
  `edited_by` mediumint(9) NOT NULL default '0',
  `percent_complete` tinyint(3) NOT NULL default '0',
  KEY `task_id` (`task_id`)
) TYPE=MyISAM AUTO_INCREMENT=1");

$result = mysql_query("CREATE TABLE `tasks_comments` (
  `task_id` mediumint(9) NOT NULL default '0',
  `u_id` mediumint(9) NOT NULL default '0',
  `comment_date` int(11) NOT NULL default '0',
  `comment` mediumtext NOT NULL
) TYPE=MyISAM");

echo "<center>";
echo "<p>Addition of 'tasks' & 'tasks_comments' tables complete!";
?>

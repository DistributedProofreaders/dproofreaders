<?php
$relPath='../../../pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

echo "Creating 'project_state_stats' table...\n";
$result = mysql_query("CREATE TABLE `project_state_stats` (
  `year` smallint(4) NOT NULL default '2003',
  `month` tinyint(2) NOT NULL default '0',
  `day` tinyint(2) NOT NULL default '0',
  `date` date  NOT NULL default '2003-00-00',
  `state` varchar(50) NOT NULL default '0',
  `num_projects` int(12) NOT NULL default '0',
  `comments` varchar(255),
  KEY date (`date`),
  KEY state (`state`)
) TYPE=MyISAM") or die(mysql_error());

echo "\nDone!\n";
?>

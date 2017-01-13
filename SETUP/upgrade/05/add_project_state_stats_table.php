<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Creating 'project_state_stats' table...\n";
$result = mysqli_query(DPDatabase::get_connection(), "CREATE TABLE `project_state_stats` (
  `year` smallint(4) NOT NULL default '2003',
  `month` tinyint(2) NOT NULL default '0',
  `day` tinyint(2) NOT NULL default '0',
  `date` date  NOT NULL default '2003-00-00',
  `state` varchar(50) NOT NULL default '0',
  `num_projects` int(12) NOT NULL default '0',
  `comments` varchar(255),
  KEY date (`date`),
  KEY state (`state`)
) TYPE=MyISAM") or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";
?>

<?php
$relPath='./pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

//----------------------------------------------------------------------------------------------------------------------
$result = mysql_query("CREATE TABLE `project_state_stats` (
  `year` smallint(4) NOT NULL default '0',
  `month` tinyint(2) NOT NULL default '0',
  `day` tinyint(2) NOT NULL default '0',
  `date` date  NOT NULL default '0000-00-00',
  `state` varchar(50) NOT NULL default '0',
  `num_projects` int(12) NOT NULL default '0',
  `comments` varchar(255),
  UNIQUE date (`date`)
) TYPE=MyISAM") or die(mysql_error());


echo "<center>";
echo "<p>Addition of 'project_state_stats' table complete!";
?>

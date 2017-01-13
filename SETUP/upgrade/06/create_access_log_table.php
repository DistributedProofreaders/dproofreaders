<?php

// One-time script to create 'access_log' table

$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');

echo "Creating table 'access_log'...\n";
dpsql_query("
CREATE TABLE `access_log` (
  `timestamp` INT( 20 ) NOT NULL ,
  `subject_username` VARCHAR( 25 ) NOT NULL ,
  `modifier_username` VARCHAR( 25 ) NOT NULL ,
  `action` VARCHAR( 16 ) NOT NULL ,
  `activity` VARCHAR( 10 ) NOT NULL ,
  INDEX ( `subject_username`,`timestamp` )
) TYPE=MyISAM;
") or die("Aborting.");

echo "\nDone!\n";

?>

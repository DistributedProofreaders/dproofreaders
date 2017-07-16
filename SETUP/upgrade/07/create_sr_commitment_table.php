<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Creating 'smoothread' table...\n";
$sql = "
       CREATE TABLE smoothread (
             projectid  varchar(22) NOT NULL default '',
             user       varchar(25) NOT NULL default '',
             committed  tinyint(4)  NOT NULL default '0',
             PRIMARY KEY  (projectid, user),
             KEY project  (projectid),
             KEY user     (user)
       )  COMMENT='Each row represents an association between a user and a project regarding smoothreading.'
       ";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "\nDone!\n";
?>


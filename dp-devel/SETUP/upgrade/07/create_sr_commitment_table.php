<?php
$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

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

mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";
?>


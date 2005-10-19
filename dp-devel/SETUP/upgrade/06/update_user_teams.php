<?php
$relPath='../../../pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

echo "Adding uniqueness constraint on 'teamname' column of 'user_teams' table...\n";

mysql_query("
    ALTER TABLE user_teams
        ADD UNIQUE (teamname)
") or die(mysql_error());

echo "\nDone!\n";
?>

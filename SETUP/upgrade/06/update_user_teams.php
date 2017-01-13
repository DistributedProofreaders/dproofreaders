<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Adding uniqueness constraint on 'teamname' column of 'user_teams' table...\n";

mysql_query("
    ALTER TABLE user_teams
        ADD UNIQUE (teamname)
") or die(mysql_error());

echo "\nDone!\n";
?>

<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Adding uniqueness constraint on 'teamname' column of 'user_teams' table...\n";

mysqli_query(DPDatabase::get_connection(), "
    ALTER TABLE user_teams
        ADD UNIQUE (teamname)
") or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";
?>

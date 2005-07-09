<?php
$relPath='../../../pinc/';
include_once($relPath.'v_site.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

header('Content-type: text/plain');

echo "\n";
echo "Adding 't_last_activity' column...\n";

mysql_query("
    ALTER TABLE users
        ADD COLUMN t_last_activity INT UNSIGNED NOT NULL AFTER last_login,
        ADD INDEX (t_last_activity)
") or die(mysql_error());

echo "Initializing it to the time of last login...\n";
mysql_query("
    UPDATE users
    SET t_last_activity=last_login
") or die(mysql_error());

echo "Done!\n";

// vim: sw=4 ts=4 expandtab
?>

<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

echo "Altering 'users' table...\n";

mysql_query("
    ALTER TABLE users
        ADD INDEX (last_login),
	ADD INDEX pages_index (pagescompleted)
") or die(mysql_error());

echo "\nDone!\n";
?>

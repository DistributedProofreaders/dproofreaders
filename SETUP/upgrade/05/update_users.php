<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

echo "Altering 'users' table...\n";

mysqli_query(DPDatabase::get_connection(), "
    ALTER TABLE users
        ADD INDEX (last_login),
	ADD INDEX pages_index (pagescompleted)
") or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";
?>

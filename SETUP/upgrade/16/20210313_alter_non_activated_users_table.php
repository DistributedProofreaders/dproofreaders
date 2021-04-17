<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Increasing size of non_activated_users.password column..\n";
$sql = "
    ALTER TABLE non_activated_users
        CHANGE COLUMN user_password user_password varchar(255) NOT NULL default ''
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

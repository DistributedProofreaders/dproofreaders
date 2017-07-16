<?php

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Increasing the column length of user_password...\n";
$sql = "
    ALTER TABLE non_activated_users
        CHANGE COLUMN user_password
            user_password varchar(128) not null default '';
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab
?>

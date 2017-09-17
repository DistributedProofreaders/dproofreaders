<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Increasing size of email column in users table...\n";
$sql = "
    ALTER TABLE users
        CHANGE COLUMN email email VARCHAR(100) NOT NULL DEFAULT '';
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "Increasing size of email column in non_activated_users table...\n";
$sql = "
    ALTER TABLE non_activated_users
        CHANGE COLUMN email email VARCHAR(100) NOT NULL DEFAULT '';
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab

<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Adding referrer column to non_activated_users..\n";
$sql = "
    ALTER TABLE non_activated_users
        ADD COLUMN referrer varchar(32) NOT NULL DEFAULT '' AFTER email_updates;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "Adding http_referrer column to non_activated_users..\n";
$sql = "
    ALTER TABLE non_activated_users
        ADD COLUMN http_referrer varchar(256) NOT NULL DEFAULT '' AFTER referrer;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "Adding referrer columns to users..\n";
$sql = "
    ALTER TABLE users
        ADD COLUMN referrer varchar(32) NOT NULL DEFAULT '' AFTER email_updates;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "Adding http_referrer columns to users..\n";
$sql = "
    ALTER TABLE users
        ADD COLUMN http_referrer varchar(256) NOT NULL DEFAULT '' AFTER referrer;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab

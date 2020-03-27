<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Adding referrer_details column to non_activated_users...\n";

$sql = "
    ALTER TABLE non_activated_users
        ADD COLUMN referrer_details varchar(256) DEFAULT '' AFTER referrer;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "Adding referrer_details column to users...\n";

$sql = "
    ALTER TABLE users
        ADD COLUMN referrer_details varchar(256) DEFAULT '' AFTER referrer;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab

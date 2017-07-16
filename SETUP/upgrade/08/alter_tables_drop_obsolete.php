<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "\n";
echo "Removing obsolete 'emailupdates' column from users table...\n";

mysqli_query(DPDatabase::get_connection(), "
    ALTER TABLE users
        DROP COLUMN emailupdates
") or print(mysqli_error(DPDatabase::get_connection())."\n");


echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>

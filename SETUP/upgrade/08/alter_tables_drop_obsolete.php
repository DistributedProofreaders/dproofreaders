<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "\n";
echo "Removing obsolete 'emailupdates' column from users table...\n";

mysql_query("
    ALTER TABLE users
        DROP COLUMN emailupdates
") or print(mysql_error()."\n");


echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>

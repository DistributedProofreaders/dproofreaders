<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Making u_id INDEX in users UNIQUE..\n";
$sql = "
    ALTER TABLE users
        DROP INDEX u_id,
        ADD UNIQUE INDEX u_id (u_id);
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

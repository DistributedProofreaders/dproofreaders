<?php

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

die("WARNING: To prevent data loss, ensure 20180212-01_migrate_user_roles.php ran successfully before running this file.\nAfter it has, comment out this line and run it.\n");

// ------------------------------------------------------------

echo "Dropping columns: manager, postprocessor, sitemanager, u_top10, u_plist...\n";
$sql = "
    ALTER TABLE users
        DROP COLUMN manager,
        DROP COLUMN postprocessor,
        DROP COLUMN sitemanager,
        DROP COLUMN u_top10,
        DROP COLUMN u_plist;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab

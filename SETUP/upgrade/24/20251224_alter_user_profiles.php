<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Dropping some columns on user_profiles...\n";
$sql = "
    ALTER TABLE user_profiles
         DROP COLUMN i_res,
         DROP COLUMN i_toolbar,
         DROP COLUMN i_statusbar,
         DROP COLUMN i_newwin;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

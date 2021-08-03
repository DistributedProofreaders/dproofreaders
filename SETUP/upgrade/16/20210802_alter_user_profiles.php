<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Delete unused settings from user_profiles\n";
$sql = "
    ALTER TABLE user_profiles
        DROP COLUMN v_zoom,
        DROP COLUMN h_zoom
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

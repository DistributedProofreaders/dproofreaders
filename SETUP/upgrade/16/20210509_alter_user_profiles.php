<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Increasing size of textarea columns in user_profiles..\n";
$sql = "
    ALTER TABLE user_profiles
        CHANGE COLUMN v_tlines v_tlines tinyint unsigned default 40,
        CHANGE COLUMN v_tchars v_tchars tinyint unsigned default 65,
        CHANGE COLUMN h_tlines h_tlines tinyint unsigned default 6,
        CHANGE COLUMN h_tchars h_tchars tinyint unsigned default 70;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

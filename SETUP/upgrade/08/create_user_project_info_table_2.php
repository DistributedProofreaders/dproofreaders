<?php

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Enlarging user_project_info table...\n";
$sql = "
    ALTER TABLE user_project_info
        ADD COLUMN t_latest_home_visit INT UNSIGNED NOT NULL AFTER projectid,
        ADD COLUMN t_latest_page_event INT UNSIGNED NOT NULL AFTER t_latest_home_visit
";
echo "$sql\n";
mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// echo "Populating the new columns...\n";
// Will commit that code later.

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>

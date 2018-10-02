<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Adding a new column (iste_sr_complete) to user_project_info...\n";
$sql = "
    ALTER TABLE user_project_info
        ADD COLUMN iste_sr_complete tinyint(1) NOT NULL default '0'
        AFTER iste_sr_available;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab

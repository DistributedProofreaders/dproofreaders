<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Adding users.navbar_activity_menu\n";
$sql = "
    ALTER TABLE users ADD COLUMN navbar_activity_menu tinyint NOT NULL DEFAULT 1
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

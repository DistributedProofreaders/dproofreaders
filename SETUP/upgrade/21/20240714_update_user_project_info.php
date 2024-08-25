<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

// ------------------------------------------------------------

echo "Altering user_project_info...\n";

$sql = "
    ALTER TABLE user_project_info
        ADD COLUMN bookmark tinyint NOT NULL default 0
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

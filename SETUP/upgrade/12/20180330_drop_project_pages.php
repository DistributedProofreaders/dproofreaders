<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Dropping deprecated project_pages table...\n";
$sql = "
    DROP TABLE project_pages
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab

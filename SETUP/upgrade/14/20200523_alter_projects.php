<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Adding per-project characters to projects\n";

$sql = "
    ALTER TABLE projects ADD COLUMN custom_chars VARCHAR(64) DEFAULT ''
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()));

// vim: sw=4 ts=4 expandtab

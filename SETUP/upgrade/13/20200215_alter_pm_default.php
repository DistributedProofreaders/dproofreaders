<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "changing default i_pmdefault in users table\n";

$sql = "
    ALTER TABLE users ALTER i_pmdefault SET DEFAULT '0';
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()));

// vim: sw=4 ts=4 expandtab

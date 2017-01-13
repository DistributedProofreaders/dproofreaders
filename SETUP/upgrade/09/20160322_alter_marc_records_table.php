<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Removing original_marc and updated_marc columns from marc_records...\n";

$sql = "
    ALTER TABLE marc_records
        DROP COLUMN original_marc,
        DROP COLUMN updated_marc
";
echo "$sql\n";
mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab

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
mysql_query($sql) or die( mysql_error() );

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab

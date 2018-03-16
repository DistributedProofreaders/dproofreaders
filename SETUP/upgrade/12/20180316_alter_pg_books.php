<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Updating column types for pg_books...\n";
$sql = "
    ALTER TABLE pg_books
        CHANGE COLUMN etext_number etext_number int unsigned NOT NULL,
        CHANGE COLUMN formats formats varchar(255) NOT NULL DEFAULT ''
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab

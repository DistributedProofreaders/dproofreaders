<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Creating 'pg_books' table...\n";
$sql = "
    CREATE TABLE pg_books (
        etext_number SMALLINT UNSIGNED NOT NULL PRIMARY KEY,
        formats      TINYTEXT          NOT NULL
    )
    COMMENT='Each row represents a different PG etext'
";
mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "\nDone!\n";
?>

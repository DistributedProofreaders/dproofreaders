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
mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";
?>

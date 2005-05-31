<?php
$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

$sql = "
    CREATE TABLE pg_books (
        etext_number SMALLINT UNSIGNED NOT NULL PRIMARY KEY,
        formats      TINYTEXT          NOT NULL,
    )
    COMMENT='Each row represents a different PG etext'
";
mysql_query($sql) or die( mysql_error() );
echo "<center>Table 'pg_books' created successfully.</center>";
?>

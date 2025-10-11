<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "Dropping table pg_books...\n";
$sql = "
    DROP TABLE pg_books
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";

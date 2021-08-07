<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Updating genre to 'Science Fiction & Fantasy' where it's 'Science Fiction' ...\n";
$sql = "
    UPDATE projects
        SET genre = 'Science Fiction & Fantasy'
        WHERE genre = 'Science Fiction'
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

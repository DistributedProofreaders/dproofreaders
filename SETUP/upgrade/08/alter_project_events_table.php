<?php

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "Adding an index for timestamp...\n";
$sql = "
    ALTER TABLE `project_events`
        ADD INDEX `timestamp` ( `timestamp` )
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab
?>

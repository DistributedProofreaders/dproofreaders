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

mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab
?>

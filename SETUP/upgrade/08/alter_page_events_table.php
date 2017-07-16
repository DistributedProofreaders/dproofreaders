<?php

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "Adding an index for projectid,username...\n";
$sql = "
    ALTER TABLE `page_events`
        ADD INDEX `projectid_username` ( `projectid` , `username` )
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );


echo "Adding an index for username,project,round,time...\n";
$sql = "
    ALTER TABLE `page_events`
        ADD INDEX `username_projectid_round_time`
            ( `username` , `projectid` , `round_id` , `timestamp` )
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab
?>

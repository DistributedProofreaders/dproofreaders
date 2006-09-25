<?php

$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');

echo "Adding an index for projectid,username...\n";
$sql = "
    ALTER TABLE `page_events`
        ADD INDEX `projectid_username` ( `projectid` , `username` )
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";

echo "Adding an index for username,project,round,time...\n";
$sql = "
    ALTER TABLE `page_events`
        ADD INDEX `username_projectid_round_time`
		    ( `username` , `projectid` , `round_id` , `timestamp` )
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab
?>

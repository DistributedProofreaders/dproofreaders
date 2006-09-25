<?php

$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');


echo "Adding an index for projectid,archived,state...\n";
$sql = "
    ALTER TABLE `projects`
        ADD INDEX `projectid_archived_state`
            ( `projectid`, `archived`, `state` );
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab
?>

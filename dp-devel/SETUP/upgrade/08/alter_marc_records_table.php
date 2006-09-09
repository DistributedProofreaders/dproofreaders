<?php

$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');

echo "Adding a primary key on projectid...\n";
$sql = "
    ALTER TABLE `marc_records`
        ADD PRIMARY KEY ( `projectid` )
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>

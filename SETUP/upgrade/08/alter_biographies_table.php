<?php

$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Updating default value for last_modified column\n";
$sql = "
    ALTER TABLE biographies
        CHANGE COLUMN last_modified last_modified TIMESTAMP
            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

// ------------------------------------------------------------

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab
?>

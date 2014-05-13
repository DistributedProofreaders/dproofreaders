<?php

// Create the 'project_holds' table (initially empty).

$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');

echo "Creating project_holds table...\n";
$sql = "
    CREATE TABLE project_holds
    (
        projectid  VARCHAR(22) NOT NULL,
        state      VARCHAR(50) NOT NULL,

        PRIMARY KEY (projectid,state)
    )
";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>

<?php

$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');


echo "Creating 'daily_page_limits' table...\n";
$sql = "
    CREATE TABLE daily_page_limit
    (
        name             VARCHAR(50) NOT NULL,
        project_selector VARCHAR(256) NOT NULL,
        daily_limit      INT(8) NOT NULL,
        PRIMARY KEY (name)
    )
";
echo "$sql\n";
mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );


echo "Creating 'daily_page_limit_user_counts' table...\n";
$sql = "
    CREATE TABLE daily_page_limit_user_counts
    (
        limit_name    VARCHAR(50) NOT NULL,
        username      VARCHAR(25) NOT NULL,
        current_count INT(8) NOT NULL,
        PRIMARY KEY (limit_name, username)
    )
";
echo "$sql\n";
mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab

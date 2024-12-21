<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

// ------------------------------------------------------------

echo "Creating json_storage table...\n";

$sql = "
    CREATE TABLE json_storage (
        username varchar(25) NOT NULL,
        setting varchar(32) NOT NULL,
        value json NOT NULL,
        timestamp int NOT NULL default 0,
        PRIMARY KEY (username, setting)
    )
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

<?php

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "Creating wordcheck_events table...\n";
$sql = "
    CREATE TABLE wordcheck_events
    (
        check_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        projectid   VARCHAR(22) NOT NULL,
        timestamp   INT UNSIGNED NOT NULL,
        image       VARCHAR(12) NOT NULL,
        round_id    CHAR(2) NOT NULL,
        username    VARCHAR(25) NOT NULL,
        suggestions TEXT,
        corrections TEXT
    )
";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

$sql = "CREATE INDEX pc_compound ON wordcheck_events(projectid,timestamp,image)";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>

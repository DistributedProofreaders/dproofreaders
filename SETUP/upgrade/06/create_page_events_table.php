<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header( 'Content-type: text/plain' );

echo "Creating 'page_events' table...\n";
mysqli_query(DPDatabase::get_connection(), "
    CREATE TABLE page_events (
        event_id      INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,

        timestamp     INT UNSIGNED NOT NULL,

        projectid     VARCHAR(22) NOT NULL,
        image         VARCHAR(12) NOT NULL,

        event_type    VARCHAR(16) NOT NULL,
        username      VARCHAR(25) NOT NULL,
        round_id      CHAR(2),

        INDEX (projectid,image,round_id),
        INDEX (username,round_id)
    )
") or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>

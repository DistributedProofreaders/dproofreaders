<?php
$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
new dbConnect;

header( 'Content-type: text/plain' );

mysql_query("
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
") or die(mysql_error());

echo "page_events table created!\n";

// vim: sw=4 ts=4 expandtab
?>

<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "Creating format_preview_events...\n";
$sql = "
    CREATE TABLE `format_preview_events` (
        `event_id` int unsigned NOT NULL AUTO_INCREMENT,
        `projectid` varchar(22) NOT NULL,
        `image` varchar(12) NOT NULL,
        `round_id` char(2) NOT NULL,
        `username` varchar(25) NOT NULL,
        `timestamp` int unsigned NOT NULL,
        PRIMARY KEY (`event_id`),
        UNIQUE KEY `pc_compound` (`projectid`,`image`,`round_id`,`username`)
    )
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";

<?php

// One-time script to create 'quiz_passes' table

$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'dpsql.inc');
new dbConnect();

echo "Creating 'quiz_passes' table...\n";
dpsql_query("
    CREATE TABLE quiz_passes (
        username  VARCHAR(25) NOT NULL default '',
        date      INT(20)     NOT NULL default '0',
        quiz_page VARCHAR(15) NOT NULL default '',
        result    VARCHAR(10) NOT NULL default '',
        INDEX (username,quiz_page)
    ) TYPE=MyISAM
") or die("Aborting.");

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>

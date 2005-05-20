<?php
$relPath='./pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

mysql_query("
    ALTER TABLE projects
        ADD special             VARCHAR(20) AFTER projectid,
        ADD final_page_count    SMALLINT(4) UNSIGNED DEFAULT '0' NOT NULL,
        ADD ppverifier          VARCHAR(25),
        ADD image_provider      VARCHAR(10),
        ADD smoothread_deadline INT(20)     DEFAULT '0' NOT NULL,
        ADD up_projectid        INT(10)     DEFAULT '0',
        ADD INDEX (special)
") or die(mysql_error());

echo "<p>Addition of various fields to `projects` table is complete!";

// vim: sw=4 ts=4 expandtab
?>

<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Adding 'related_postings' column to 'tasks' table...\n";
$result = mysql_query("ALTER TABLE `tasks` ADD `related_postings` MEDIUMTEXT NOT NULL") or die(mysql_error());

echo "Initializing it...\n";
$empty_array_value = base64_encode(serialize(array()));
mysql_query("
    UPDATE tasks
    SET related_postings='$empty_array_value'
    WHERE related_postings=''
") or die(mysql_error());

echo "\nDone!\n";
?>

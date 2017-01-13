<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

echo "Altering 'queue_defns' table...\n";

mysql_query("
    ALTER TABLE queue_defns
        MODIFY COLUMN ordering MEDIUMINT(5) NOT NULL DEFAULT '0'
") or die(mysql_error());

echo "\nDone!\n";
?>

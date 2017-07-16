<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Creating 'job_logs' table...\n";
$result = mysqli_query(DPDatabase::get_connection(), "CREATE TABLE `job_logs` (
`filename` VARCHAR( 40 ) DEFAULT '0' NOT NULL ,
`tracetime` INT( 12 ) UNSIGNED DEFAULT '0' NOT NULL ,
`event` VARCHAR( 20 ) DEFAULT '0' NOT NULL ,
`comments` VARCHAR( 255 ) 
) TYPE = MYISAM") or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";
?>

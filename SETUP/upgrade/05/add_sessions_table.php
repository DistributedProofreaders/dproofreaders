<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Creating 'sessions' table...\n";
$result = mysqli_query(DPDatabase::get_connection(), "CREATE TABLE `sessions` (
`sid` varchar(32) NOT NULL default '',
`expiration` int(11) NOT NULL default '0',
`value` text NOT NULL,
PRIMARY KEY  (`sid`)
)") or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";
?>

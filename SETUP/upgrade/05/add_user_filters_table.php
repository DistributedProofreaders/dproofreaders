<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Creating 'user_filters' table...\n";
$result = mysqli_query(DPDatabase::get_connection(), "CREATE TABLE `user_filters` (
`username` varchar(25) NOT NULL default '',
`filtertype` varchar(25) NOT NULL default '',
`value` text NOT NULL default '',
PRIMARY KEY  (`username`,`filtertype`)
)") or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";
?>

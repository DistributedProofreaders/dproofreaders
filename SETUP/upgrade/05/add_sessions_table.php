<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Creating 'sessions' table...\n";
$result = mysql_query("CREATE TABLE `sessions` (
`sid` varchar(32) NOT NULL default '',
`expiration` int(11) NOT NULL default '0',
`value` text NOT NULL,
PRIMARY KEY  (`sid`)
)") or die(mysql_error());

echo "\nDone!\n";
?>

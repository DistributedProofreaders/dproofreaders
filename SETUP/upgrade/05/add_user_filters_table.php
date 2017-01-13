<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Creating 'user_filters' table...\n";
$result = mysql_query("CREATE TABLE `user_filters` (
`username` varchar(25) NOT NULL default '',
`filtertype` varchar(25) NOT NULL default '',
`value` text NOT NULL default '',
PRIMARY KEY  (`username`,`filtertype`)
)") or die(mysql_error());

echo "\nDone!\n";
?>

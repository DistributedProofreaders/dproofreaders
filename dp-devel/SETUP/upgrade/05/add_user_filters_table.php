<?
$relPath='../../../pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

echo "Creating 'user_filters' table...\n";
$result = mysql_query("CREATE TABLE `user_filters` (
`username` varchar(25) NOT NULL default '',
`filtertype` varchar(25) NOT NULL default '',
`value` text NOT NULL default '',
PRIMARY KEY  (`username`,`filtertype`)
)");

echo "\nDone!\n";
?>

<?
$relPath='./pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

//----------------------------------------------------------------------------------------------------------------------
$result = mysql_query("CREATE TABLE `user_filters` (
`username` varchar(25) NOT NULL default '',
`filtertype` varchar(25) NOT NULL default '',
`value` text NOT NULL default '',
PRIMARY KEY  (`username`,`filtertype`)
)");

echo "<center>";
echo "<p>Addition of new table 'user_filters' complete!";
?>

<?
$relPath='../pinc/';
include($relPath.'connect.inc');
include($relPath.'f_dpsql.inc');
$db_Connection = new dbConnect();
dpsql_dump_table( "pagestats" );
?>

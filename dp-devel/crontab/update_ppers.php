<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

$result = mysql_query("SELECT DISTINCT(postproofer) FROM projects WHERE postproofer is not null");
while (list($postproofer) = mysql_fetch_row($result)) {
	mysql_query("UPDATE users SET postprocessor = 'yes' WHERE username = '$postproofer'");
	if ( mysql_affected_rows() > 0 )
	{
		echo "Added: $postproofer<br>";
	}
}

echo "<br><center>Update of Post Processors Complete!</center>";

?>


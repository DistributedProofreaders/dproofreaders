<?php
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');

// check that caller is localhost or bail
if(!requester_is_localhost())
    die("You are not authorized to perform this request.");

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


<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

$result = mysql_query("SELECT DISTINCT(postproofer) FROM projects WHERE postproofer is not null");
while ($row = mysql_fetch_assoc($result)) {
	$isPPQuery = mysql_query("SELECT postprocessor FROM users WHERE username = '".$row['postproofer']."'");
	$isPP = mysql_result($isPPQuery, 0, "postprocessor");
	if ($isPP != "yes") {
		$updatePPQuery = mysql_query("UPDATE users SET postprocessor = 'yes' WHERE username = '".$row['postproofer']."'");
		echo "Added: ".$row['postproofer']."<br>";
	}
}

echo "<br><center>Update of Post Processors Complete!</center>";

?>


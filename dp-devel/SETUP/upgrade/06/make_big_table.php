<?php
set_time_limit(0);
error_reporting(0);

$relPath='./pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

$result = mysql_query("SELECT projectid FROM projects");

while ($row = mysql_fetch_assoc($result)) {
	$result1 = mysql_query("SELECT * FROM ".$row['projectid']."");
	if ($result1 == FALSE || mysql_num_rows($result1) == 0) {
		echo $row['projectid']." -- Not moved due to either missing table or 0 rows<br>";
	} else {
		$projectid = substr($row['projectid'], -13);
		while ($row1 = mysql_fetch_assoc($result1)) {
			$result2 = mysql_query("INSERT INTO project_pages (projectid, fileid, image, master_text, round1_text, round2_text, round1_user, round2_user, round1_time, round2_time, state, b_user, b_code) VALUES ('$projectid', '".$row1['fileid']."', '".$row1['image']."', '".mysql_escape_string($row1['master_text'])."', '".mysql_escape_string($row1['round1_text'])."', '".mysql_escape_string($row1['round2_text'])."', '".$row1['round1_user']."', '".$row1['round2_user']."', ".$row1['round1_time'].", ".$row1['round2_time'].", '".$row1['state']."', '".$row1['b_user']."', ".$row1['b_code'].")");
		}
		echo $row['projectid']." -- Moved to project_pages table<br>";
		$result3 = mysql_query("DROP TABLE ".$row['projectid']."");
	}
}

echo "<br><h1><center><b>Finished!</b></center></h1><br>";

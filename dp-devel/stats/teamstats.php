<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

$today = getdate();
$midnight = mktime(0,0,0,$today['mon'],$today['mday'],$today['year']);
$prev_midnight = $midnight - 86400;

//Find out if the script has been run once already for today
$result = mysql_query("SELECT MAX(date_updated) FROM user_teams_stats");
	if (mysql_result($result,0,0) == $midnight && empty($_GET['testing'])) {
		echo "<center>This script has already been run today!</center>";
	} else {
	//Update user_teams_stats with previous days page count
	$result = mysql_query("SELECT id, teamname, page_count FROM user_teams");
		while($row = mysql_fetch_assoc($result)) {
			if ($row['id'] != 1) {
				$prevDayCount = mysql_query("SELECT page_count FROM user_teams_stats WHERE date_updated = $prev_midnight && team_id = ".$row['id']."");
				$todaysCount = $row['page_count'] - mysql_result($prevDayCount,0,"page_count");
				$updateCount = mysql_query("INSERT INTO user_teams_stats (team_id, date_updated, page_count) VALUES (".$row['id'].", $midnight, $todaysCount)");
			}
		}
	//Total all of a teams page counts and average it
	$result = mysql_query("SELECT id FROM user_teams");
		while($row = mysql_fetch_assoc($result)) {
			if ($row['id'] != 1) {
				$avgPageCount = mysql_query("SELECT AVG(page_count) AS avgCount FROM user_teams_stats WHERE team_id = ".$row['id']."");
				$avgCount = mysql_result($avgPageCount,0,"avgCount");
				$updateAvgCount = mysql_query("UPDATE user_teams SET daily_average = $avgCount WHERE id = ".$row['id']."");
			}
		}
	}
?>
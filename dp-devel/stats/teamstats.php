<?
$relPath='./../pinc/';
include($relPath.'misc.inc');
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

header('Content-type: text/plain');

$testing = array_get($_GET, 'testing', FALSE);

function maybe_query( $query )
{
	global $testing;
	if ($testing)
	{
		echo "$query\n";
		return TRUE;
	}
	else
	{
		return mysql_query( $query );
	}
}

$today = getdate();
$midnight = mktime(0,0,0,$today['mon'],$today['mday'],$today['year']);

//Find out if the script has been run once already for today
$result = mysql_query("SELECT MAX(date_updated) FROM user_teams_stats");
$max_update = mysql_result($result,0,0);
	if ($max_update == $midnight && !$testing) {
		echo "<center>This script has already been run today!</center>";
	} else {
		//Update user_teams_stats with previous days page count; 
		// also, since we're going round the loop anyway, let's update the
		// teams daily average as well
		$now = time();
		$result = mysql_query("SELECT id, page_count, created FROM user_teams");
		while($row = mysql_fetch_assoc($result)) {
			if ($row['id'] != 1) {
				$prevDayCount = mysql_query("SELECT total_page_count FROM user_teams_stats WHERE date_updated = $max_update && team_id = ".$row['id']."");
				$todaysCount = $row['page_count'] - mysql_result($prevDayCount,0,"total_page_count");
				$updateCount = maybe_query("INSERT INTO user_teams_stats (team_id, date_updated, daily_page_count, total_page_count) VALUES (".$row['id'].", $midnight, $todaysCount, ".$row['page_count'].")");

				//Calculate the average daily team proofing as total pages / total days
				$daysInExistence = number_format(floor(($now - $row['created'])/86400));
				if ($daysInExistence > 0) {
				        $avgCount = $row['page_count']/$daysInExistence;
				} else {
					$avgCount = 0;
				}
				$updateAvgCount = maybe_query("UPDATE user_teams SET daily_average = $avgCount WHERE id = ".$row['id']."");
			}
		}

	//Update the page count rank for the previous day
	$result = mysql_query("SELECT id, page_count FROM user_teams WHERE id != 1 ORDER BY page_count DESC");
		$i = 1;
		$rankArray = "";

		while ($row = mysql_fetch_assoc($result)) {
			$team_id = $row['id'];
			if ($row['page_count'] == $lastcompleted) {
				$rankArray['rank'][$team_id] = $lastrank;
				$lastrank = $lastrank;
    			} else {
    				$rankArray['rank'][$team_id] = $i;
    				$lastrank = $i;
   			}
    			$lastcompleted = $row['page_count'];
    			if ($i == 1) { $lastrank = 1; }
    			$i++;
		}

	$result = mysql_query("SELECT id FROM user_teams");
		while($row = mysql_fetch_assoc($result)) {
			if ($row['id'] != 1) {
				$team_id = $row['id'];
				$rank = $rankArray['rank'][$team_id];
				$updateRank = maybe_query("UPDATE user_teams_stats SET rank = $rank WHERE team_id = $team_id && date_updated = $midnight");
			}
		}
	}
?>

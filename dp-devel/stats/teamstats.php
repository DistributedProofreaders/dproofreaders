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
		// Normalize whitespace
		// (mainly to remove newlines and indentation)
		$query = preg_replace('/\s+/', ' ', trim($query));
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
		$rankArray = teams_get_page_tally_ranks();
		$result = mysql_query("SELECT id, page_count, created FROM user_teams");
		while($row = mysql_fetch_assoc($result)) {
			$team_id = $row['id'];
			if ($team_id != 1) {
				$rank = $rankArray[$team_id];
				$prevDayCount = mysql_query("
					SELECT total_page_count
					FROM user_teams_stats
					WHERE date_updated = $max_update && team_id = $team_id
				");
				$todaysCount = $row['page_count'] - mysql_result($prevDayCount,0,"total_page_count");
				$updateCount = maybe_query("
					INSERT INTO user_teams_stats
					(team_id, date_updated, daily_page_count, total_page_count, rank)
					VALUES ($team_id, $midnight, $todaysCount, ".$row['page_count'].", $rank)
				");

				//Calculate the average daily team proofing as total pages / total days
				$daysInExistence = number_format(floor(($now - $row['created'])/86400));
				if ($daysInExistence > 0) {
				        $avgCount = $row['page_count']/$daysInExistence;
				} else {
					$avgCount = 0;
				}
				$updateAvgCount = maybe_query("
					UPDATE user_teams
					SET daily_average = $avgCount
					WHERE id = $team_id
				");
			}
		}
	}
?>

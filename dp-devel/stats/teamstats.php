<?
$relPath='./../pinc/';
include($relPath.'misc.inc');
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'page_tally.php');
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
$result = mysql_query("
	SELECT MAX(date_updated)
	FROM user_teams_stats
");
$max_update = mysql_result($result,0,0);
	if ($max_update == $midnight && !$testing) {
		echo "<center>This script has already been run today!</center>";
	} else {
		//Update user_teams_stats with previous days page count; 
		$rankArray = teams_get_page_tally_ranks();

		$result = mysql_query("
			SELECT team_id, total_page_count
			FROM user_teams_stats
			WHERE date_updated = $max_update
		");
		while (list($team_id, $prev_tally_value) = mysql_fetch_row($result)) {
			$prevDayCount[$team_id] = $prev_tally_value;
		}

		$result = mysql_query("
			SELECT holder_id, tally_value
			FROM current_tallies
			WHERE holder_type='T' AND tally_name='P'
		");
		while(list($team_id, $current_P_tally) = mysql_fetch_row($result)) {
			if ($team_id != 1) {
				$rank = $rankArray[$team_id];
				$todaysCount = $current_P_tally - $prevDayCount[$team_id];
				$updateCount = maybe_query("
					INSERT INTO user_teams_stats
					SET
						team_id          = $team_id,
						date_updated     = $midnight,
						daily_page_count = $todaysCount,
						total_page_count = $current_P_tally,
						rank             = $rank
				");
			}
		}
	}
?>

<?
$relPath='./../pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'page_tally.php');
include_once($relPath.'connect.inc');
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
	SELECT MAX(timestamp)
	FROM past_tallies
	WHERE holder_type='U' AND tally_name='P'
");
$max_update = mysql_result($result,0,0);
	if ($max_update == $midnight && !$testing) {
		echo "<center>This script has already been run today!</center>\n";
		maybe_query("INSERT INTO job_logs (filename, tracetime, event, comments) VALUES ('mbrstats.php', ".time().", 'FAIL', 'Already been run today!')");
	} else {
		$tracetime=time();
		maybe_query("INSERT INTO job_logs (filename, tracetime, event, comments) VALUES ('mbrstats.php', $tracetime, 'BEGIN', 'Started generating member statistics for $midnight')");
		//Update the page count rank for the previous day
		$rankArray = users_get_page_tally_ranks();

		//Update past_tallies with previous days page count
		$result = mysql_query("
			SELECT holder_id, tally_value
			FROM past_tallies
			WHERE
				timestamp = $max_update
				AND holder_type='U'
				AND tally_name='P'
		");
		while (list($u_id, $prev_tally_value) = mysql_fetch_row($result)) {
			$prevDayCount[$u_id] = $prev_tally_value;
		}

		$result = mysql_query("
			SELECT holder_id, tally_value
			FROM current_tallies
			WHERE holder_type='U' AND tally_name='P'
		");
		while(list($u_id, $current_P_tally) = mysql_fetch_row($result)) {
			$rank = $rankArray[$u_id];
			$todaysCount = $current_P_tally - $prevDayCount[$u_id];
			$updateCount = maybe_query("
				INSERT INTO past_tallies
				SET
					timestamp   = $midnight,
					holder_type = 'U',
					holder_id   = $u_id,
					tally_name  = 'P',
					tally_delta = $todaysCount,
					tally_value = $current_P_tally,
					tally_rank  = $rank
			");
		}
		$tracetimea = time();
		$tooktime = $tracetimea - $tracetime;
		maybe_query("INSERT INTO job_logs (filename, tracetime, event, comments) VALUES ('mbrstats.php', $tracetimea, 'END', 'Started at $tracetime, took $tooktime seconds total')");
	}
?>

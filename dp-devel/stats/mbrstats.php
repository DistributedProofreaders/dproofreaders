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
$result = mysql_query("SELECT MAX(date_updated) FROM member_stats");
$max_update = mysql_result($result,0,0);
	if ($max_update == $midnight && !$testing) {
		echo "<center>This script has already been run today!</center>\n";
		maybe_query("INSERT INTO job_logs (filename, tracetime, event, comments) VALUES ('mbrstats.php', ".time().", 'FAIL', 'Already been run today!')");
	} else {
		$tracetime=time();
		maybe_query("INSERT INTO job_logs (filename, tracetime, event, comments) VALUES ('mbrstats.php', $tracetime, 'BEGIN', 'Started generating member statistics for $midnight')");
		//Update the page count rank for the previous day
		$result = mysql_query("SELECT u_id, pagescompleted FROM users ORDER BY pagescompleted DESC");
		$rankArray = "";
		$i = 1;

		while ($row = mysql_fetch_assoc($result)) {
			$user_id = $row['u_id'];
			if ($row['pagescompleted'] == 0) { $rankArray[$user_id] = 0; continue; }
			if ($row['pagescompleted'] == $lastcompleted) {
				$rankArray[$user_id] = $lastrank;
				$lastrank = $lastrank;
    			} else {
    				$rankArray[$user_id] = $i;
    				$lastrank = $i;
   			}
    			$lastcompleted = $row['pagescompleted'];
    			if ($i == 1) { $lastrank = 1; }
    			$i++;
		}

	//Update member_stats with previous days page count
	$result = mysql_query("SELECT u_id, total_pagescompleted FROM member_stats WHERE date_updated = $max_update");
		while ($row = mysql_fetch_assoc($result)) {
			$prevDayCount[$row['u_id']]['total_pagescompleted'] = $row['total_pagescompleted'];
		}

	$result = mysql_query("SELECT u_id, pagescompleted FROM users");
		while($row = mysql_fetch_assoc($result)) {
			$todaysCount = $row['pagescompleted'] - $prevDayCount[$row['u_id']]['total_pagescompleted'];
			$updateCount = maybe_query("INSERT INTO member_stats (u_id, date_updated, daily_pagescompleted, total_pagescompleted, rank) VALUES (".$row['u_id'].", $midnight, $todaysCount, ".$row['pagescompleted'].", ".$rankArray[$row['u_id']].")");
		}
		$tracetimea = time();
		$tooktime = $tracetimea - $tracetime;
		maybe_query("INSERT INTO job_logs (filename, tracetime, event, comments) VALUES ('mbrstats.php', $tracetimea, 'END', 'Started at $tracetime, took $tooktime seconds total')");
	}
?>

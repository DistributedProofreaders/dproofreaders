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

$tracetime=time();
maybe_query("INSERT INTO job_logs (filename, tracetime, event, comments) VALUES ('mbrstats.php', $tracetime, 'BEGIN', 'Started generating member statistics for $midnight')");

$err = $users_P_page_tallyboard->take_snapshot( $midnight, $testing );
if ( $err )
{
	echo "<center>This script has already been run today!</center>\n";
	maybe_query("INSERT INTO job_logs (filename, tracetime, event, comments) VALUES ('mbrstats.php', ".time().", 'FAIL', 'Already been run today!')");
}
else
{
	$tracetimea = time();
	$tooktime = $tracetimea - $tracetime;
	maybe_query("INSERT INTO job_logs (filename, tracetime, event, comments) VALUES ('mbrstats.php', $tracetimea, 'END', 'Started at $tracetime, took $tooktime seconds total')");
}
?>

<?
// (This is a replacement for dailystats.pl.)

// The 'pagestats' table records the number of pages proofed on any given day,
// where "day" is defined as the interval between two successive midnights,
// Pacific time? (So it's usually a 24 hour period, but might be 23 or 25 when
// we go to Daylight Savings Time?)
//
// This script updates the 'pagestats' table for yesterday, and any previous
// days that appear to need it.

$relPath='../pinc/';
include($relPath.'connect.inc');
include_once('./pages_proofed.inc');
new dbConnect();

$EOL = "\n";
$testing_this_script=$_GET['testing'];

if ($testing_this_script)
{
    echo "<pre>", $EOL;
}

// Figure out the last day (X) for which a count was taken.
$res = mysql_query( 'SELECT MAX(date) FROM pagestats WHERE pages != 0' )
    or die(mysql_error());
list($X_date) = mysql_fetch_array($res);

list($X_year,$X_month,$X_day) = explode('-',$X_date);

// Get a timestamp for the most recent midnight (local time).
$today_start_ts = mktime(0,0,0);

if ($testing_this_script)
{
    echo 'Last counted date was ', $X_date, $EOL;
    if (0)
    {
	echo 'Backing up a few days', $EOL;
	$X_day -= 4;
    }
    else
    {
	echo "Using 2004-06-20 instead", $EOL;
	$X_year  = 2004;
	$X_month = 6;
	$X_day   = 20;
    }

    // echo "And pretending that today is 2003-07-03.", $EOL;
    // $today_start_ts = mktime(0,0,0, 7,3,2003);
}

$tracetime = time();
mysql_query("INSERT INTO job_logs (filename, tracetime, event, comments)
               VALUES ('update_daily_page_counts.php', $tracetime, 'BEGIN', '')");




for ( $d = 1; ; $d++ )
{
    // Consider the day (Y) that is $d days after $X_date.
    $Y_start_ts = mktime(0,0,0,$X_month, $X_day+$d, $X_year);
    $Y_date = date('Y-m-d', $Y_start_ts );

    // If Y is today (or later), we're done.
    if ($Y_start_ts >= $today_start_ts)
    {
        break;
    }

    $Y_end_ts = mktime(0,0,0,$X_month,$X_day+$d+1,$X_year);

    $total_n_pages_proofed = get_n_pages_proofed( $Y_start_ts, $Y_end_ts, $n_projects );

    $update_query =
       "UPDATE pagestats SET pages=$total_n_pages_proofed WHERE date='$Y_date'";

    if ($testing_this_script)
    {
        echo $update_query, $EOL;
    }
    else
    {
        echo $update_query, $EOL;
        mysql_query($update_query) or die(mysql_error());
    }


$tracetimea = time();
$sofar = $tracetimea - $tracetime;
mysql_query("INSERT INTO job_logs (filename, tracetime, event, comments)
               VALUES ('update_daily_page_counts.php', $tracetimea, 'ENDED a DAY', 'time so far $sofar seconds')");


}

$tracetimea = time();
$sofar = $tracetimea - $tracetime;
mysql_query("INSERT INTO job_logs (filename, tracetime, event, comments)
               VALUES ('update_daily_page_counts.php', $tracetimea, 'END ALL', 'time so far $sofar seconds')");



if ($testing_this_script)
{
    echo "</pre>", $EOL;
}

?>

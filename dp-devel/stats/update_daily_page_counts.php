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
new dbConnect();

$EOL = "\n";
$testing_this_script=TRUE;

if ($testing_this_script)
{
    echo "<pre>", $EOL;
}

if ($testing_this_script)
{
    $X_date = '2003-01-01';
}
else
{
    // Figure out the last day (X) for which a count was taken.
    $res = mysql_query( 'SELECT MAX(date) FROM pagestats WHERE pages != 0' )
        or die(mysql_error());
    list($X_date) = mysql_fetch_array($res);
    if ($testing_this_script)
    {
        echo 'Last counted date was ', $X_date, $EOL;
    }
}

list($X_year,$X_month,$X_day) = explode('-',$X_date);

// Get a timestamp for the most recent midnight (local time).
$today_start_ts = mktime(0,0,0);

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

    $total_n_pages_proofed = get_n_pages_proofed( $Y_start_ts, $Y_end_ts );

    if ($testing_this_script)
    {
        echo "update pagestats: $Y_date $total_n_pages_proofed", $EOL;
    }
}

if ($testing_this_script)
{
    echo "</pre>", $EOL;
}

function get_n_pages_proofed( $start_ts, $end_ts )
// Return the total number of pages proofed between the two timestamps.
{
    $total_n_pages_proofed = 0;

    // Only consider projects that have been modified after Jan 1, 2003
    // (i.e. on or after 2003-01-02 00:00:00 UTC).
    // Older projects have a different table structure.
    $project_cutoff_ts = gmmktime(0,0,0,1,2,2003);

    $res = mysql_query("SELECT projectid FROM projects WHERE modifieddate >= $project_cutoff_ts" )
        or die(mysql_error());

    while( $project_row = mysql_fetch_array($res) )
    {
        list($projectid) = $project_row;

        $res2 = mysql_query("
            SELECT COUNT(*) FROM $projectid
            WHERE 
            state='save_first' 
                AND round1_time >= $start_ts
                AND round1_time <  $end_ts
            OR
            state='save_second'
                AND round2_time >= $start_ts
                AND round2_time <  $end_ts
            ");

        if (!$res2)
        {
            // Probably the project's page-table does not exist.
            // Not sure why.
            continue;
        }

        list($n_pages_proofed) = mysql_fetch_array($res2);

        $total_n_pages_proofed += $n_pages_proofed;
    }

    return $total_n_pages_proofed;
}
?>

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

if ($testing_this_script)
{
    echo 'Last counted date was ', $X_date, $EOL;
    echo 'Backing up a few days', $EOL;

    $X_day -= 4;
}

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
}

if ($testing_this_script)
{
    echo "</pre>", $EOL;
}

function get_n_pages_proofed( $start_ts, $end_ts )
// Return the total number of pages proofed between the two timestamps.
// (Takes about ?? seconds.) haven't measured since queries were changed
{
    $total_n_pages_proofed = 0;

    // Only consider projects that have not been archived.
    $res = mysql_query("SELECT projectid FROM projects WHERE archived='0'" )
        or die(mysql_error());

    while( $project_row = mysql_fetch_array($res) )
    {
        list($projectid) = $project_row;

	// page finished in R1 may have since progressed to R2

        $pdoneR1 = mysql_query("SELECT COUNT(*) FROM $projectID WHERE         
		round1_time >= $start_ts
            AND round1_time <  $end_ts
            AND (state='save_first' OR state LIKE '%_second%')
	");   

        if (!$pdoneR1)
        {
            // Probably the project's page-table does not exist.
            // Not sure why.
	    $pages1 = 0;
            continue;
        } else {
            $row1 = mysql_fetch_row($pdoneR1);
	    $pages1 = $row1[0];
	}

        
        $pdoneR2 = mysql_query("SELECT COUNT(*) FROM $projectID WHERE
         	  state='save_second' 
            AND round2_time >= $start_ts
            AND round2_time <  $end_ts 
         ");

        if (!$pdoneR2)
        {
            // Probably the project's page-table does not exist.
            // Not sure why.
	    $pages2 = 0;
            continue;
        } else {
            $row2 = mysql_fetch_row($pdoneR2);
            $pages2 = $row2[0];
	}
        
        $n_pages_proofed = $pages1+$pages2;
        
        $total_n_pages_proofed += $n_pages_proofed;
    }

    return $total_n_pages_proofed;
}
?>

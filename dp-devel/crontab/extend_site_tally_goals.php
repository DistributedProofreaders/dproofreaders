<?
$relPath='./../pinc/';
include($relPath.'f_dpsql.inc');
include($relPath.'connect.inc');
new dbConnect();

$res = dpsql_query("
    SELECT MAX(date) FROM site_tally_goals
") or die("Aborting");
list($current_max_date) = mysql_fetch_row($res);

if ( is_null($current_max_date) )
{
    // The site_tally_goals table is empty.
    // We should probably assume that the
    // site admins want to keep it that way.
    exit;
}

// Ensure that the table is defined for at least the next 35 days.
$desired_max_date = strftime('%Y-%m-%d',strtotime('+35 days'));

// What I'd *like* to be able to write:
// for ( $d = $current_max_date+1; $d <= today() + 35; $d++ )

while(1)
{
    $date = strftime( '%Y-%m-%d', strtotime( "$current_max_date + 1 day" ) );
    if ( $date > $desired_max_date ) break;

    // Replicate the latest set of goals for one more day.
    dpsql_query("
        INSERT INTO site_tally_goals
            SELECT '$date', tally_name, goal
            FROM site_tally_goals
            WHERE date='$current_max_date'
    ") or die("Aborting");

    $current_max_date = $date;
}

// vim: sw=4 ts=4 expandtab
?>

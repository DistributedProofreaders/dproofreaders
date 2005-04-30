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

$res2 = dpsql_query("
    SELECT tally_name, goal
    FROM site_tally_goals
    WHERE date='$current_max_date'
") or die("Aborting");

$values_list = '';

// Ensure that the table is defined for at least the next 35 days.
$desired_max_date = strftime('%Y-%m-%d',strtotime('+35 days'));

// What I'd *like* to be able to write:
// for ( $d = $current_max_date+1; $d <= today() + 35; $d++ )

for ( $i = 1; ; $i++ )
{
    $date = strftime( '%Y-%m-%d', strtotime( "$current_max_date + $i day" ) );
    if ( $date > $desired_max_date ) break;

    mysql_data_seek( $res2, 0 ) or die(mysql_error());
    while ( list($tally_name,$goal) = mysql_fetch_row($res2) )
    {
        if (!empty($values_list)) $values_list .= ',';
        $values_list .= "( '$date', '$tally_name', $goal )\n";
    }

    $values_list .= "\n";
}

if ( empty($values_list) ) return;

dpsql_query("
    INSERT INTO site_tally_goals
    VALUES
    $values_list
") or die("Aborting");

// vim: sw=4 ts=4 expandtab
?>

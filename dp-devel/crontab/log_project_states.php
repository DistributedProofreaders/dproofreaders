<?
$relPath='../pinc/';
include($relPath.'project_states.inc');
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');

$EOL = "\n";
$testing_this_script=$_GET['mytesting'];


// See if this has been run once today or not
$res = mysql_query( 'SELECT MAX(date) FROM project_state_stats WHERE num_projects != 0' )
    or die(mysql_error());
$X_date = mysql_result($res,0); // If table is empty, this returns NULL.
echo $X_date, $EOL;
if ($X_date == date('Y-m-d')) {
    echo "Already run once for today.", $EOL;
    if (! $testing_this_script)
    {
        echo "switching to testing mode", $EOL;
        $testing_this_script = TRUE;
    }
}


$yr = date('Y');
$mth = date('m');
$dy = date('d');
$dte = date('Y-m-d');

// The SELECT we do below will only return counts for project-states that are
// currently occupied. (I.e., there's at least one project in that state.)
// But we want project_state_stats to get a row for every possible state for
// every day, so we must do something extra to record zero for the unoccupied
// states.

// Initialize $num_projects_in_state_ to zero for every currently-defined state.
$num_projects_in_state_ = array();
foreach ( $PROJECT_STATES_IN_ORDER as $state )
{
    $num_projects_in_state_[$state] = 0;
}

// Get the number of projects in each (currently-occupied) state.
$result = mysql_query ("SELECT state, count(*) FROM projects GROUP BY state ORDER BY state");

while (list ($state, $num_projects) = mysql_fetch_row ($result)) {
    $num_projects_in_state_[$state] = $num_projects;
}

// $num_projects_in_state_ now has an entry for every defined state and for
// every occupied state. (The occupied states should be a subset of the defined
// states, but you never know.)
// Insert a row into project_state_stats for each of those entries.

echo "INSERT INTO project_state_stats SET year=$yr, month=$mth, day=$dy, date='$dte', ...", $EOL;
foreach ( $num_projects_in_state_ as $state => $num_projects )
{
    $np = sprintf( "%4d", $num_projects );
    echo "    num_projects=$np, state='$state'", $EOL;

    $insert_query = "
        INSERT INTO project_state_stats
        SET year=$yr, month=$mth, day=$dy, date='$dte', state='$state', num_projects=$num_projects
    ";

    if (! $testing_this_script)
    {
        mysql_query($insert_query) or die(mysql_error());
    }
}

// vim: sw=4 ts=4 expandtab
?>

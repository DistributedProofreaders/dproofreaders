<?
$relPath='../pinc/';
include($relPath.'connect.inc');
new dbConnect();

$EOL = "\n";
$testing_this_script=$_GET['mytesting'];


// script assumes the table has at least one row in it

if ($testing_this_script)
{
    echo "<pre>", $EOL;
}

// See if this has been run once today or not
$res = mysql_query( 'SELECT MAX(date) FROM project_state_stats WHERE num_projects != 0' )
    or die(mysql_error());
$X_date = mysql_result($res,0);
echo $X_date;
if ($X_date == date('Y-m-d')) {
    echo "Already run once for today ";
    if (! $testing_this_script)
    {
        echo "switching to testing mode <br><br>";
        echo "<pre>", $EOL;
        $testing_this_script = TRUE;
    }
}


$yr = date('Y');
$mth = date('m');
$dy = date('d');
$dte = date('Y-m-d');

// get counts of projects for each state

$result = mysql_query ("SELECT state, count(*) FROM projects GROUP BY state ORDER BY state");

while (list ($state, $num_projects) = mysql_fetch_row ($result)) {

    $insert_query =
       "INSERT INTO project_state_stats (year, month, day , date , state ,  num_projects)
        VALUES ($yr, $mth, $dy, '$dte', '$state', $num_projects)";

    if ($testing_this_script)
    {
        echo $insert_query, $EOL;
    }
    else
    {
        echo $insert_query, $EOL;
        mysql_query($insert_query) or die(mysql_error());
    }
}

// add rows for those states which have no projects today

// (the following query assumes states are not ever 'retired';
// if in the future they are, a where clause explicitly excluding them will work
// in the short term, until eventually (after all states have shown again
// at least once) this can be replaced with a where clause based on
// date > some_date)

$result = mysql_query ("SELECT distinct state FROM project_state_stats ORDER BY state");

while (list ($state) = mysql_fetch_row ($result)) {


    $qry = "SELECT count(*) as cnt FROM project_state_stats WHERE state = '$state' and date = '".date('Y-m-d')."'";
    echo $qry;
    $result2 = mysql_query ($qry);

    $cnt = mysql_result($result2,0,'cnt');
    echo $cnt;
    // no row for this state yet today
    if (( $cnt) == '0') {

        $insert_query =
            "INSERT INTO project_state_stats (year, month, day , date , state ,  num_projects)
            VALUES ($yr, $mth, $dy, '$dte','$state', 0)";

        if ($testing_this_script)
        {
            echo $insert_query, $EOL;
        }
        else
        {
            echo $insert_query, $EOL;
            mysql_query($insert_query) or die(mysql_error());
        }
    }
}

if ($testing_this_script)
{
    echo "</pre>", $EOL;
}

// vim: sw=4 ts=4 expandtab
?>

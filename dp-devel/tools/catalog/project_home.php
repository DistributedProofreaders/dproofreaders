<?php
$relPath="../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');
include($relPath.'page_states.inc');
include($relPath.'f_project_states.inc');

function format_time( $time_sse )
// $time_sse (expressed in seconds since epoch)
{
    if ($time_sse == 0)
    {
	return '';
    }
    else
    {
	return gmdate( 'Y-m-d H:i:s', $time_sse );
    }
}

$page_states = array(
    UNAVAIL_FIRST,
    AVAIL_FIRST,
    OUT_FIRST,
    TEMP_FIRST,
    SAVE_FIRST,
    BAD_FIRST,

    UNAVAIL_SECOND,
    AVAIL_SECOND,
    OUT_SECOND,
    TEMP_SECOND,
    SAVE_SECOND,
    BAD_SECOND
);

$projectid = $_GET['project'];

$rows = mysql_query( "SELECT * FROM projects WHERE projectid='$projectid'") or die(mysql_error());

if ( mysql_numrows($rows) == 0 )
{
    echo "no such projectid $projectid<br>\n";
}
else
{
    $project_res = mysql_fetch_array( $rows, MYSQL_ASSOC );

    $nameofwork  = $project_res['nameofwork'];

    echo "<head><title>DP home page of \"$nameofwork\"</title></head>\n";
    echo "<body>\n";
    echo "<h1>DP home page of \"$nameofwork\"</h1>\n";

    echo "<p>(Imagine a much nicer presentation for this page.)</p>\n";

    // ----------------------------------------------------------------

    echo "<h3>Information about this project:</h3>\n";

    echo "<table border=1>\n";
    foreach ($project_res as $key => $value)
    {
	if ($key == 'modifieddate')
	{
	    $value = format_time($value);
	}
        echo "<tr><td>$key</td><td>$value</td></tr>\n";
    }
    echo "<tr><td>Project Directory</td><td><a href='$projects_url/$projectid'>here</a></td></tr>\n";
    echo "</table>\n";

    // ----------------------------------------------------------------

    $proj_state = $project_res['state'];
    $proj_round = projectStateRound($proj_state);

    echo "<h3>Things you can currently do with this project:</h3> (these would all be links)\n";

    echo "<ul>\n";

    if ( 1 )
    {
        echo "<li> Discuss this project in its dedicated <a href='../proofers/project_topic.php?project=$projectid'>Forum</a>.</li>\n";
    }

    if ( $proj_state != PROJ_COMPLETE && $proj_state != PROJ_COMPLETE )
    {
        echo "<li> Sign up to be notified when [something happens].</li>\n";
    }

    if ( $proj_round != 'GB' and $proj_round != 'COMPLETE' and
        $pguser == $project_res['username'] )
    {
        echo "<li> (You are the project manager.) Upload images or OCR results.</li>\n";
    }

    if ( 1 )
    {
        echo "<li> View page-images <a href='$projects_url/$projectid/index.html'>online</a>.</li>\n";
        echo "<li> Download a zip of page-images.</li>\n";
    }

    if ( $proj_round == 'FIRST' || $proj_round == 'SECOND' )
    {
        if ( $proj_round == 'FIRST' )
        {
            $available_state = PROJ_PROOF_FIRST_AVAILABLE;
        }
        else
        {
            $available_state = PROJ_PROOF_SECOND_AVAILABLE;
        }
        echo "<li><a href='../proofers/projects.php?project=$projectid&proofstate=$available_state'>Start Proofing!</a></li>\n";
        echo "<li> Re-proof pages (if you've proofed any).</li>\n";
    }

    if ( $proj_round != 'NEW' )
    {
        echo "<li>View page-texts online.</li>\n";
    }

    if ( $proj_round == 'PP' )
    {
        echo "<li> Download a zip of joined page-texts.</li>\n";
    }

    if ( $proj_state == PROJ_POST_AVAILABLE ) // and $pguser is qualified
    {
        echo "<li> Check it out for post-proofing.</li>\n";
    }

    if ( $proj_state == PROJ_POST_CHECKED_OUT and
        $pguser == $project_res['checkedoutby'] )
    {
        echo "<li> (You are the post-processor.) Check it in.</li>\n";
    }

    if ( $proj_state == PROJ_PG_POSTED || $proj_state == PROJ_COMPLETE )
    {
        echo "<li> Download the text (zipped or not) from Project Gutenberg</li>\n";
    }
    echo "</ul>\n";

    // ----------------------------------------------------------------

    echo "<hr>\n";

    echo "<h3>Page Information</h3>\n";

    $res = mysql_query( "SELECT count(*) AS num_pages FROM $projectid") or die(mysql_error());
    $num_pages = mysql_result($res,0,'num_pages');
    echo "<p>Total number of pages: $num_pages</p>\n";

    echo "<p>Number of pages in various states:</p>\n";
    echo "<table border=1>\n";
    foreach ($page_states as $page_state)
    {
        $res = mysql_query( "SELECT count(*) AS num_pages FROM $projectid WHERE state='$page_state'") or die(mysql_error());
        $num_pages = mysql_result($res,0,'num_pages');
        if ( $num_pages != 0 )
        {
            echo "<tr><td>$num_pages</td><td>$page_state</td></tr>\n";
        }
    }
    echo "</table>\n";

    echo "</body>\n";
    echo "</html>\n";
}

?>

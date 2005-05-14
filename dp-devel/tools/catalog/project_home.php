<?php
$relPath="../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');

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

    include_once($relPath.'slim_header.inc');
    slim_header($nameofwork);

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
    $project_phase = get_phase_containing_project_state($proj_state);

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

    if ( $project_phase != 'GB' and $project_phase != 'COMPLETE' and
        $pguser == $project_res['username'] )
    {
        echo "<li> (You are the project manager.) Upload images or OCR results.</li>\n";
    }

    if ( 1 )
    {
        echo "<li> View page-images <a href='$projects_url/$projectid/index.html'>online</a>.</li>\n";
        echo "<li> Download a zip of page-images.</li>\n";
    }

    if ( $project_phase == 'PAGE_EDITING' )
    {
        $round = get_Round_for_project_state($proj_state);
        echo "<li><a href='$code_url/project.php?id=$projectid&amp;expected_state={$round->project_available_state}'>Start Proofreading!</a></li>\n";
        echo "<li> Re-proofread pages (if you've proofread any).</li>\n";
    }

    if ( $project_phase != 'NEW' )
    {
        echo "<li>View page-texts online.</li>\n";
    }

    if ( $project_phase == 'PP' )
    {
        echo "<li> Download a zip of joined page-texts.</li>\n";
    }

    if ( $proj_state == PROJ_POST_FIRST_AVAILABLE ) // and $pguser is qualified
    {
        echo "<li> Check it out for post-processing.</li>\n";
    }

    if ( $proj_state == PROJ_POST_FIRST_CHECKED_OUT and
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
    foreach ($PAGE_STATES_IN_ORDER as $page_state)
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

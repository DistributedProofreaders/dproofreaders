<?php
$relPath="../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');
include($relPath.'page_states.inc');
include($relPath.'f_project_states.inc');

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
            echo "<tr><td>$page_state</td><td>$num_pages</td></tr>\n";
        }
    }
    echo "</table>\n";

    echo "<p>Info for individual pages:</p>\n";

    // fileid, image, master_text, state
    // round1_text, round1_user, round1_time
    // round2_text, round2_user, round2_time

    echo "<table border=1>\n";
    echo "<tr>\n";
    echo "  <th>image</th>\n";
    echo "  <th>fileid</th>\n";
    echo "  <th>state</th>\n";
    echo "  <th>master_text</th>\n";
    echo "  <th>round1_user</th>\n";
    echo "  <th>round1_time</th>\n";
    echo "  <th>round1_text</th>\n";
    echo "  <th>round2_user</th>\n";
    echo "  <th>round2_time</th>\n";
    echo "  <th>round2_text</th>\n";
    echo "</tr>\n";
    $query = "SELECT image, fileid, state, round1_user, round1_time, round2_user, round2_time FROM $projectid ORDER BY image ASC";
    $pages_res = mysql_query($query) or die(mysql_error());
    while ( $page_res = mysql_fetch_array( $pages_res, MYSQL_ASSOC ) )
    {
        echo "<tr>\n";
        echo "  <td>{$page_res['image']}</td>\n";
        echo "  <td>{$page_res['fileid']}</td>\n";
        echo "  <td>{$page_res['state']}</td>\n";
        echo "  <td><a href='get_page_text.php?project=$projectid&page_image={$page_res['image']}&which=master'>text</a>\n";
        echo "  <td>{$page_res['round1_user']}</td>\n";
        echo "  <td>{$page_res['round1_time']}</td>\n";
        echo "  <td><a href='get_page_text.php?project=$projectid&page_image={$page_res['image']}&which=round1'>text</a>\n";
        echo "  <td>{$page_res['round2_user']}</td>\n";
        echo "  <td>{$page_res['round2_time']}</td>\n";
        echo "  <td><a href='get_page_text.php?project=$projectid&page_image={$page_res['image']}&which=round2'>text</a>\n";
        echo "</tr>\n";
    }
    echo "</table>\n";

    $nameofwork  = $project_res['nameofwork'];

    echo "</body>\n";
    echo "</html>\n";
}

?>

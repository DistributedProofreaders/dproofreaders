<?PHP
$relPath='../../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'project_states.inc');

define("MESSAGE_INFO",0);
define("MESSAGE_WARNING",1);
define("MESSAGE_ERROR",2);

error_reporting(E_ALL);

// get an array of round IDs
$rounds=array_keys($Round_for_round_id_);

// defaults
$default_sampleLimit = 0;
$default_days = 100;

// load any data passed into the page
$username = @$_REQUEST["username"];
$work_round_id = @$_REQUEST["work_round_id"];
$review_round_id = @$_REQUEST["review_round_id"];
$sampleLimit = array_get($_REQUEST,"sample_limit",$default_sampleLimit);
$days  = array_get($_REQUEST,"days",$default_days);

// if the user isn't a site manager or an access request reviewer
// they can only access their own pages
if (!(user_is_a_sitemanager() || user_is_an_access_request_reviewer()))
{
    $username = $pguser;
}


// start the page
$title = _('Reviewing work');

$no_stats = 1;
theme( $title, 'header' );

echo "<h1>$title</h1>\n";

// show form
echo "<form action='review_work.php' method='GET'>";
echo "<table>";
if (user_is_a_sitemanager() || user_is_an_access_request_reviewer()) 
{
    // only let site admins or reviewers to access non-self records
    echo  "<tr>";
    echo   "<td>" . _("Username") . "</td>";
    echo   "<td><input name='username' type='text' size='26' value='$username'></td>";
    echo  "</tr>";
}
echo  "<tr>";
echo   "<td>" . _("Work Round") . "</td>";
echo   "<td><select name='work_round_id'>";
_echo_round_select($rounds,$work_round_id);
echo    "</select>";
echo  "</tr>";
echo  "<tr>";
echo   "<td>" . _("Review Round") . "</td>";
echo   "<td><select name='review_round_id'>";
_echo_round_select(array_slice($rounds,1),$review_round_id);
echo     "</select>";
echo  "</tr>";
echo  "<tr>";
echo   "<td>" . _("Max days since last save") . "</td>";
echo   "<td><input name='days' type='text' size='4' value='$days'></td>";
echo  "</tr>";
echo  "<tr>";
echo   "<td>" . _("Max diffs to show") . "</td>";
echo   "<td><input name='sample_limit' type='text' size='4' value='$sampleLimit'></td>";
echo  "</tr>";
echo "</table>";
echo "<input type='submit' value='Search'>";
echo "</form>";

function _echo_round_select($rounds,$selected) {
    foreach($rounds as $round) {
        echo "<option value='$round'";
        if($round == $selected) echo " selected";
        echo ">$round</option>";
    }
}

// if not all the required values are set (ie: not passed into the script)
// stop the page here
if(empty($username) ||
   empty($work_round_id) ||
   empty($review_round_id)) {
    theme('', 'footer');
    exit;
}
if (empty($sampleLimit))
{
    $sampleLimit = $default_sampleLimit;
}
if (empty($days))
{
    $days = $default_days; 
}

// confirm the review_round_id is later than work_round_id
if(array_search($review_round_id,$rounds)<=array_search($work_round_id,$rounds)) {
    echo "<p class='error'>" . _("Review Round should be a round later than Work Round.") . "</p>";
    theme('', 'footer');
    exit;
}


echo "<hr>";

// we should have valid information at this point
$work_round   = get_Round_for_round_id($work_round_id);
$review_round = get_Round_for_round_id($review_round_id);
$time_limit = time() - $days * 24 * 60 * 60;

$res2 = dpsql_query("
    SELECT
        page_events.projectid,
        state,
        nameofwork,
        deletion_reason,
        FROM_UNIXTIME(MAX(timestamp)) AS time_of_latest_save
    FROM page_events LEFT OUTER JOIN projects USING (projectid)
    WHERE round_id='$work_round->id' AND 
          page_events.username='$username' AND 
          event_type='saveAsDone' AND
          timestamp>$time_limit
    GROUP BY page_events.projectid
    ORDER BY time_of_latest_save DESC
") or die("Aborting");

$num_projects = mysql_num_rows($res2);
echo "<p>" . sprintf(_("<b>%d</b> projects with pages saved in <b>%s</b> by <b>%s</b> within the last <b>%d</b> days."), $num_projects, $work_round->id, $username, $days) . "</p>";

// ---------------------------------------------
// snippets for use in queries
$has_been_saved_in_review_round = "(state='$review_round->page_save_state'";
for ( $rn = $review_round->round_number+1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
{
    $round = get_Round_for_round_number($rn);
    $has_been_saved_in_review_round .= " OR state LIKE '{$round->id}.%'";
}
$has_been_saved_in_review_round .= ")";
// echo "$has_been_saved_in_review_round<br>\n";

$there_is_a_diff = "
    $work_round->text_column_name
    !=
    BINARY $review_round->text_column_name
";

// ---------------------------------------------

echo "<table border='1'>";

echo "<tr>";
echo "<th>" . ("Title") . "</th>";
echo "<th>" . ("Current State") . "</th>";
echo "<th>" . ("Last Saved") . "</th>";
echo "<th>" . sprintf(_("Pages saved by user in %s"),$work_round->id) . "</th>";
echo "<th>" . sprintf(_("Pages saved by others in %s"),$review_round->id) . "</th>";
echo "<th>" . _("Pages with differences") . "</th>";
echo "<th>" . _("$sampleLimit most recent diffs") . "</th>";
echo "</tr>";

$total_n_saved   = 0;
$total_n_latered = 0;
$total_n_w_diff  = 0;
$total_valid_projects = 0;

$messages = array();  // will contain error messages
// each message will be an array of: project, state, other info, level

$projects_done = array(); // the projects that we've done rows for

// go through the list of projects with pages saved in the work round, according
// to the page_events table
while ( list($projectid, $state, $nameofwork, $deletion_reason, $time_of_latest_save) = mysql_fetch_row($res2) )
{
    // $url = "$code_url/project.php?id=$projectid&amp;detail_level=4";
    $url = "$code_url/tools/project_manager/page_detail.php?project=$projectid&amp;select_by_user=$username&amp;select_by_round=$work_round_id";

    // if the project has been deleted, find out whether it was merged into another one
    // and if so, operate on the one it was merged into
    // this may give us duplicates in the list. This relies on the deletion
    // reason containing 'merged' and the projectid.

    $was_merged = ($state == 'project_delete') &&  
        str_contains($deletion_reason, 'merged');
    // see if the deletion reason contains "merged", and if so look for the projectid
    if ($was_merged && 
        (1 == preg_match('/\b(projectID[0-9a-f]{13})\b/', $deletion_reason, $matches))) 
    {
        $deleted_projectid = $projectid;
        $projectid = $matches[0];
        $url = "$code_url/tools/project_manager/page_detail.php?project=$projectid&amp;select_by_user=$username&amp;select_by_round=$work_round_id";
        $deleted_state = $state;
        $deleted_nameofwork = $nameofwork;
        $deleted_url = "$code_url/tools/project_manager/project.php?id=$deleted_projectid";
        $dres = mysql_query("SELECT state, nameofwork FROM projects WHERE projectid = '$projectid'");
        list($state, $nameofwork) = mysql_fetch_row($dres);
        // OK, the information is now all for the project that the deleted one was merged into
        $messages[] = array("<a href='$deleted_url'>$deleted_nameofwork</a>",
                            $deleted_state,
                            sprintf(_("Merged into %s"), "<a href='$url'>$nameofwork</a>"),
                            MESSAGE_INFO);
    }
    // what do we do if it was merged but we haven't found a projectid? TODO

    // see if we've already done this project
    if ("" != @$projects_done[$projectid])
    {
        // we've done it before
        continue; // go on to next project
    }
    
    // haven't done it yet
    $projects_done[$projectid] = $projectid;

    // see if the pages table exists
    if (!mysql_query("DESCRIBE $projectid"))
    {
        // table doesn't exist. We are not interested.
        $messages[] = array("<a href='$url'>$nameofwork</a>",
                            $state,
                            _("Page information no longer available"),
                            MESSAGE_ERROR);
        continue;
    }

    // see if it actually went through the review round. If there are no users in that
    // column, then it hasn't gone through the round.
    $review_round_result = mysql_query("
        SELECT COUNT(*) 
        FROM $projectid 
        WHERE {$review_round->user_column_name} != ''
       ");
    list($done_in_rround) = mysql_fetch_row($review_round_result);
    if (0 == $done_in_rround) {
        // hasn't been proofed in review round. We are not interested.
        $messages[] = array("<a href='$url'>$nameofwork</a>",
                            $state,
                            sprintf(_("Has not been proofed in %s"), $review_round_id),
                            MESSAGE_INFO);
        continue;
    }

    // OK, we are definitely interested in this project
    $total_valid_projects ++;

    // $query = "select count(*) from $projectid where {$work_round->user_column_name}='$username' and $has_been_saved_in_review_round and $there_is_a_diff";
    $query = "
        SELECT
            COUNT(*),
            IFNULL( SUM($has_been_saved_in_review_round), 0 ),
            IFNULL( SUM($has_been_saved_in_review_round AND $there_is_a_diff), 0 )
        FROM $projectid
        WHERE {$work_round->user_column_name}='$username'";
    $res3 = mysql_query($query);
    if ( $res3 !== FALSE )
    {
        list( $n_saved, $n_latered, $n_with_diff ) = mysql_fetch_row($res3);
        mysql_free_result($res3);
        if($n_latered > 0)
            $n_with_diff_percent = sprintf("%d",($n_with_diff/$n_latered)*100);
        else $n_with_diff_percent = 0;
        $table_found = 1;
    }
    else
    {
        die( mysql_error() );
    }

    // now get the $sampleLimit most recent pages that are different
    // don't use page_events because of the problems with merged projects
    $diffLinkString="";
    if ($sampleLimit >0 )
    {
        $query="
           SELECT image 
           FROM $projectid AS proj 
           WHERE $has_been_saved_in_review_round AND 
                 $there_is_a_diff AND
                 {$work_round->user_column_name} = '$username'
           ORDER BY {$work_round->time_column_name} DESC, image 
           LIMIT $sampleLimit";
        $result = mysql_query($query);
        if ( $result !== FALSE ) {
            $diffLinkString="";
            while( list($image) = mysql_fetch_row($result) ) {
                $diffLinkString.="<a href='../project_manager/diff.php?project=$projectid&amp;image=$image&amp;L_round_num=$work_round->round_number&amp;R_round_num=$review_round->round_number'>$image</a> ";
            }
            mysql_free_result($result);
        } 
    }

    // not sure why the pages that have been saved in the review round
    // are highlighted as that data doesn't really tell me much
    // ... but it shows the evaluator which projects to look at
    // ... but not necessary with revamp of page
    
    $n_latered_bg = ( $n_latered   > 0 ? "" : "" );
    $n_w_diff_bg  = ( $n_with_diff > 0 ? "bgcolor='#ccffcc'" : "" );
        
    $total_n_saved   += $n_saved;
    $total_n_latered += $n_latered;
    $total_n_w_diff  += $n_with_diff;

    echo "<tr>";
    echo "<td $n_latered_bg><a href='$url'>$nameofwork</a></td>";
    echo "<td nowrap>";
    echo project_states_text( $state );
    echo "</td>";
    echo "<td>$time_of_latest_save</td>";
    echo "<td align='center'>$n_saved</td>";
    echo "<td align='center' $n_latered_bg>$n_latered</td>";
    echo "<td align='center' $n_w_diff_bg>$n_with_diff ($n_with_diff_percent%)</td>";
    echo "<td $n_w_diff_bg>$diffLinkString</td>";
    echo "</tr>";
    echo "\n";
} // end of doing each project

if($total_n_latered > 0) 
{
    $total_n_w_diff_percent = sprintf("%d",($total_n_w_diff/$total_n_latered)*100);
}
else 
{
    $total_n_w_diff_percent = 0;
}

echo "<tr>";
echo "<th>$total_valid_projects</th>";
echo "<th></th>";
echo "<th></th>";
echo "<th align='center'>$total_n_saved</th>";
echo "<th align='center'>$total_n_latered</th>";
echo "<th align='center'>$total_n_w_diff ($total_n_w_diff_percent%)</th>";
echo "</tr>";

echo "</table>";

// show messages
$total_invalid_projects = count($messages); 
if($total_invalid_projects) {
    echo "<h2>" . _("Other projects") . "</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Project</th><th>Current state</th><th>Status</th></tr>";
    foreach($messages as $message)
    {
        echo "<tr><td>{$message[0]}</td>";
        echo "<td nowrap>";
        echo project_states_text( $message[1] );
        echo "</td>";
        echo"<td>{$message[2]}</td></tr>";
    }
    echo "<tr><th>$total_invalid_projects</th><th></th><th></th></tr>";

    echo "</table>";
}

echo "<br>";

theme('', 'footer');

// vim: sw=4 ts=4 expandtab
?>

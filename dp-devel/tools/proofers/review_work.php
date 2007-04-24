<?PHP
$relPath='../../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');

define("MESSAGE_INFO",0);
define("MESSAGE_WARNING",1);
define("MESSAGE_ERROR",2);

error_reporting(E_ALL);

// get an array of round IDs
$rounds=array_keys($Round_for_round_id_);

// load any data passed into the page
$username = @$_REQUEST["username"];
$work_round_id = @$_REQUEST["work_round_id"];
$review_round_id = @$_REQUEST["review_round_id"];
$sampleLimit = 6;  // NB hard coded: the number of recent diffs to display per project

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

// if not all values are set (ie: not passed into the script)
// stop the page here
if(empty($username) ||
   empty($work_round_id) ||
   empty($review_round_id)) {
    theme('', 'footer');
    exit;
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


$res1 = dpsql_query("
    SELECT COUNT(*), COUNT(DISTINCT projectid)
    FROM page_events
    WHERE round_id='$work_round->id' AND username='$username' AND event_type='saveAsDone'
") or die("Aborting");
list($n_page_saves,$n_distinct_projects) = mysql_fetch_row($res1);
mysql_free_result($res1);

// Note that some of these saves might have been later undone by
// the user reopening the page or the PM clearing the page.
// So far, we don't care too much about that.

echo "<p>" . sprintf(_("Compared %d page-saves between rounds %s and %s within %d projects."),$n_page_saves,$work_round->id,$review_round->id,$n_distinct_projects) . "</p>";

$res2 = dpsql_query("
    SELECT
        page_events.projectid,
        state,
        nameofwork,
        FROM_UNIXTIME(MAX(timestamp)) AS time_of_latest_save
    FROM page_events LEFT OUTER JOIN projects USING (projectid)
    WHERE round_id='$work_round->id' AND page_events.username='$username' AND event_type='saveAsDone'
    GROUP BY page_events.projectid
    ORDER BY time_of_latest_save DESC
") or die("Aborting");

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
echo "<th>" . _("Recent Diff Samples") . "</th>";
echo "</tr>";

$total_n_saved   = 0;
$total_n_latered = 0;
$total_n_w_diff  = 0;

$messages = array();  // will contain error messages

// go through the list of projects with pages saved in the work round, according
// to the page_events table
while ( list($projectid, $state, $nameofwork, $time_of_latest_save) = mysql_fetch_row($res2) )
{
    // $url = "$code_url/project.php?id=$projectid&amp;detail_level=4";
    $url = "$code_url/tools/project_manager/page_detail.php?project=$projectid&amp;select_by_user=$username";

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
            $n_with_diff_percent = sprintf("%d",($n_with_diff/$n_saved)*100);
        else $n_with_diff_percent = 0;
        $table_found = 1;
    }
    else
    {
        // something went wrong
        if ( mysql_errno() == 1146 )
        {
            $messages[sprintf(_("Pages table no longer exists for: %s"),$nameofwork)] = MESSAGE_ERROR;
            $n_saved = $n_latered = $n_with_diff  = $n_with_diff_percent = 0;
            $table_found = 0;
        }
        else
        {
            die( mysql_error() );
        }
    }

    // now get the $sampleLimit most recent pages that are different
    $query="
           SELECT DISTINCT pe.image 
           FROM page_events AS pe, $projectid AS proj 
           WHERE pe.image = proj.image AND
                 username='$username' AND 
                 event_type='saveAsDone' AND
                 $has_been_saved_in_review_round AND 
                 $there_is_a_diff AND
                 pe.username = proj.{$work_round->user_column_name} 
           ORDER BY timestamp DESC, image 
           LIMIT $sampleLimit";
    $result = mysql_query($query);
    if ( $result !== FALSE ) {
        $diffLinkString="";
        while( list($image) = mysql_fetch_row($result) ) {
            $diffLinkString.="<a href='../project_manager/diff.php?project=$projectid&amp;image=$image&amp;L_round_num=$work_round->round_number&amp;R_round_num=$review_round->round_number'>$image</a> ";
        }
        mysql_free_result($result);
    } else {
        $diffLinkString="";
    }

// not sure why the pages that have been saved in the review round
// are highlighted as that data doesn't really tell me much

//    $n_latered_bg = ( $n_latered   > 0 ? "bgcolor='#ccffcc'" : "" );

    $n_latered_bg = ''; 
    $n_w_diff_bg  = ( $n_with_diff > 0 ? "bgcolor='yellow'" : "" );

    $total_n_saved   += $n_saved;
    $total_n_latered += $n_latered;
    $total_n_w_diff  += $n_with_diff;

    echo "<tr>";
    if($table_found)
        echo "<td><a href='$url'>$nameofwork</a></td>";
    else
        echo "<td>$nameofwork</td>";
    echo "<td>$state</td>";
    echo "<td>$time_of_latest_save</td>";
    echo "<td align='center'>$n_saved</td>";
    echo "<td align='center' $n_latered_bg>$n_latered</td>";
    echo "<td align='center' $n_w_diff_bg>$n_with_diff ($n_with_diff_percent%)</td>";
    echo "<td $n_w_diff_bg>$diffLinkString</td>";
    echo "</tr>";
    echo "\n";
}

if($total_n_latered > 0) 
{
    $total_n_w_diff_percent = sprintf("%d",($total_n_w_diff/$total_n_saved)*100);
}
else 
{
    $total_n_w_diff_percent = 0;
}

echo "<tr>";
echo "<th></th>";
echo "<th></th>";
echo "<th></th>";
echo "<th align='center'>$total_n_saved</th>";
echo "<th align='center'>$total_n_latered</th>";
echo "<th align='center'>$total_n_w_diff ($total_n_w_diff_percent%)</th>";
echo "</tr>";

echo "</table>";

// show error messages
if(count($messages)) {
    echo "<h2>" . _("Messages returned") . "</h2>";
    foreach($messages as $message => $messageClass)
        echo "<p class='messageClass'>$message</p>";
}

echo "<br>";

theme('', 'footer');

// vim: sw=4 ts=4 expandtab
?>

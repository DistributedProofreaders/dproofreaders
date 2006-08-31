<?PHP
$relPath='../../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');

error_reporting(E_ALL);

if (!(user_is_a_sitemanager() || user_is_an_access_request_reviewer())) die("permission denied");

$username = @$_REQUEST['username'];

if (empty($username))
{
    echo "<form>";
    echo "username: <input name='username' type='text' size='26'>";
    echo "<input type='submit'>";
    echo "</form>";
    exit;
}

$work_round_id   = @$_REQUEST['work_round_id'];
$review_round_id = @$_REQUEST['review_round_id'];

$work_round   = get_Round_for_round_id($work_round_id);
$review_round = get_Round_for_round_id($review_round_id);

if ( is_null($work_round  ) ) die( "bad work_round_id='$work_round_id'" );
if ( is_null($review_round) ) die( "bad review_round_id='$review_round_id'" );

$title = sprintf( _('Reviewing %s work of %s'), $work_round->id, $username );

$no_stats = 1;
theme( $title, 'header' );

echo "<h2>$title</h2>\n";

$res1 = dpsql_query("
    SELECT COUNT(*), COUNT(DISTINCT projectid)
    FROM page_events
    WHERE round_id='$work_round->id' AND username='$username' AND event_type='saveAsDone'
") or die("Aborting");
list($n_page_saves,$n_distinct_projects) = mysql_fetch_row($res1);

// Note that some of these saves might have been later undone by
// the user reopening the page or the PM clearing the page.
// So far, we don't care too much about that.

echo "$n_page_saves page-saves in $n_distinct_projects projects<br>\n";
echo "<br>\n";

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

$has_been_saved_in_review_round = "(state='$review_round->page_save_state'";
for ( $rn = $review_round->round_number+1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
{
    $round = get_Round_for_round_number($rn);
    $has_been_saved_in_review_round .= " OR state LIKE '{$round->id}.%'";
}
$has_been_saved_in_review_round .= ")";
// echo "$has_been_saved_in_review_round<br>\n";

$there_is_a_diff = "BINARY
    REPLACE($work_round->text_column_name,'\r\n','\n')
    !=
    REPLACE($review_round->text_column_name,'\r\n','\n')
";

// ---------------------------------------------

echo "<table border='1'>";

echo "<tr>";
echo "<th>title</th>";
echo "<th>curr state</th>";
echo "<th>time of latest save</th>";
echo "<th># saved by user in $work_round->id</th>";
echo "<th># saved by other in $review_round->id</th>";
echo "<th># of those with a diff</th>";
echo "</tr>";

$total_n_saved   = 0;
$total_n_latered = 0;
$total_n_w_diff  = 0;

while ( list($projectid, $state, $nameofwork, $time_of_latest_save) = mysql_fetch_row($res2) )
{
    // $url = "$code_url/project.php?id=$projectid&amp;detail_level=4";
    $url = "$code_url/tools/project_manager/page_detail.php?project=$projectid&amp;select_by_user=$username";

    $res3 = mysql_query("
        SELECT
            COUNT(*),
            IFNULL( SUM($has_been_saved_in_review_round), 0 ),
            IFNULL( SUM($has_been_saved_in_review_round AND $there_is_a_diff), 0 )
        FROM $projectid
        WHERE {$work_round->user_column_name}='$username'
    ");
    if ( $res3 !== FALSE )
    {
        list( $n_saved, $n_latered, $n_with_diff ) = mysql_fetch_row($res3);
    }
    else
    {
        // something went wrong
        if ( mysql_errno() == 1146 )
        {
            echo "(pages table no longer exists for: $nameofwork)<br>\n";
            $n_saved = $n_latered = $n_with_diff = 0;
        }
        else
        {
            die( mysql_error() );
        }
    }

    $n_latered_bg = ( $n_latered   > 0 ? "bgcolor='#ccffcc'" : "" );
    $n_w_diff_bg  = ( $n_with_diff > 0 ? "bgcolor='#ccffcc'" : "" );

    $total_n_saved   += $n_saved;
    $total_n_latered += $n_latered;
    $total_n_w_diff  += $n_with_diff;

    echo "<tr>";
    echo "<td><a href='$url'>$nameofwork</a></td>";
    echo "<td>$state</td>";
    echo "<td>$time_of_latest_save</td>";
    echo "<td align='center'>$n_saved</td>";
    echo "<td align='center' $n_latered_bg>$n_latered</td>";
    echo "<td align='center' $n_w_diff_bg>$n_with_diff</td>";
    echo "</tr>";
    echo "\n";
}

echo "<tr>";
echo "<th></th>";
echo "<th></th>";
echo "<th></th>";
echo "<th align='center'>$total_n_saved</th>";
echo "<th align='center'>$total_n_latered</th>";
echo "<th align='center'>$total_n_w_diff</th>";
echo "</tr>";

echo "</table>";

echo "<br>";

theme('', 'footer');

// vim: sw=4 ts=4 expandtab
?>

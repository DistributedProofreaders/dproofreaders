<?php
$relPath="./../../pinc/";
include_once($relPath.'dpsql.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'TallyBoard.inc');
include_once($relPath.'page_tally.inc');
include_once('common.inc');

// Initialize the graph before anything else.
// This makes use of the jpgraph cache if enabled.
// Argument to init_pages_graph is the cache timeout in minutes.
$graph = init_pages_graph(60);

new dbConnect();

$tally_name = @$_GET['tally_name'];
if (empty($tally_name))
{
    die("parameter 'tally_name' is unset/empty");
}

$site_tallyboard = new TallyBoard( $tally_name, 'S' );

$now_timestamp = time();
$now_assoc = getdate($now_timestamp);

$curr_y = $now_assoc['year'];
$curr_m = $now_assoc['mon'];

switch ( @$_GET['timeframe'] )
{
    case 'curr_month':
        $start_timestamp = mktime( 0,0,0, $curr_m,   1, $curr_y );
        $end_timestamp   = mktime( 0,0,0, $curr_m+1, 1, $curr_y );
        $title_timeframe = strftime( _('%B %Y'), $now_timestamp );
        break;

    case 'prev_month':
        $start_timestamp = mktime( 0,0,0, $curr_m-1, 1, $curr_y );
        $end_timestamp   = mktime( 0,0,0, $curr_m,   1, $curr_y );
        $title_timeframe = strftime( _('%B %Y'), $start_timestamp );
        break;

    case 'all_time':
        $start_timestamp = 0;
        $end_timestamp   = mktime( 0,0,0, $curr_m+1, 1, $curr_y );
        $title_timeframe = _('since stats began');
        break;

    default:
        die("bad 'timeframe' value: '$title_timeframe'");
}

$cumulative_or_increments = @$_GET['cori'];
switch ( $cumulative_or_increments )
{
    case 'increments':
        $main_title = _('Pages Done Per Day');
        break;

    case 'cumulative':
        $main_title = _('Cumulative Pages Done');
        break;

    default:
        die("bad 'cori' value: '$cumulative_or_increments'");
}

// -----------------------------------------------------------------------------

$result = dpsql_query(
    select_from_site_past_tallies_and_goals(
        $tally_name,
        "SELECT {date}, tally_delta, goal",
        "WHERE $start_timestamp < timestamp AND timestamp <= $end_timestamp",
        "",
        "ORDER BY timestamp",
        ""
    )
);

list($datax,$datay1,$datay2) = dpsql_fetch_columns($result);

if ( $cumulative_or_increments == 'cumulative' )
{
    $datay1 = array_accumulate( $datay1 );
    $datay2 = array_accumulate( $datay2 );

    // The accumulated 'actual' for today and subsequent days is bogus,
    // so delete it.
    $date_today = strftime( '%Y-%m-%d', $now_timestamp );
    for ( $i = 0; $i < count($datax); $i++ )
    {
        if ( $datax[$i] >= $date_today )
        {
            unset($datay1[$i]);
        }
    }
}

if (empty($datay1)) {
    $datay1[0] = 0;
}


// Calculate a 21-day simple moving average for 'increments' graphs
if ( $cumulative_or_increments == 'increments' )
{
    // to ensure we have enough data to use, go back 21 days before the start date
    $where_start_timestamp = $start_timestamp - (21 * 60*60*24);

    $sql = "
        SELECT t1.timestamp,
            (SELECT round(sum(t2.tally_delta)/count(t2.tally_delta))
            FROM past_tallies as t2
            WHERE t2.holder_type = 'S'
                AND t2.holder_id = '1'
                AND t2.tally_name = '$tally_name'
                AND datediff(from_unixtime(t1.timestamp), from_unixtime(t2.timestamp)) between 0 and 21 )
            as '21daysma'
        FROM past_tallies as t1
        WHERE t1.holder_type = 'S'
            AND t1.tally_name = '$tally_name'
            AND t1.holder_id = '1'
            AND t1.timestamp > $where_start_timestamp
        ORDER BY t1.timestamp desc
        ";
    $res = mysql_query($sql);

    // store the results in a date-based array we can use to populate the
    // graph's data array
    while( $result = mysql_fetch_assoc($res) )
    {
        $average_lookup[strftime("%Y-%m-%d",$result["timestamp"])]=$result["21daysma"];
    }
    mysql_free_result($res);

    // don't go past the current date
    $end_timestamp = min( $end_timestamp, time() );

    // loop through each day in the graph and pull in the SMA if it exists
    for($dateTimestamp=$start_timestamp; $dateTimestamp<=$end_timestamp; $dateTimestamp+=(60*60*24))
    {
        $day = strftime( '%Y-%m-%d', $dateTimestamp );
        if(isset($average_lookup[$day]))
            $moving_average[]=$average_lookup[$day];
        else
            $moving_average[]=0;
    }
}
else
{
    $moving_average=null;
}

// if no data was returned from the SELECT, create an empty dataset
// otherwise we get an unsightly jpgraph error
if (empty($datax)) {
    // set arrays to empty before populating them
    $datax = $datay1 = array();
    $datay2 = null;

    // don't go past the current date
    $end_timestamp = min( $end_timestamp, time() );

    // iterate through the days of the specified month
    for($dateTimestamp=$start_timestamp; $dateTimestamp<=$end_timestamp; $dateTimestamp+=(60*60*24))
    {
        $datax[] = strftime( '%Y-%m-%d', $dateTimestamp );
        $datay1[] = 0;
    }
}

draw_pages_graph(
    $graph,
    $datax,
    $datay1,
    $datay2,
    $moving_average,
    _("21-day SMA"),
    'daily',
    $cumulative_or_increments,
    "$main_title ($title_timeframe)"
);


// vim: sw=4 ts=4 expandtab
?>

<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'TallyBoard.inc');
include_once($relPath.'page_tally.inc'); // get_page_tally_names()
include_once($relPath.'misc.inc'); // get_enumerated_param()
include_once('common.inc');

$valid_tally_names = array_keys(get_page_tally_names());
$tally_name = get_enumerated_param($_GET, 'tally_name', null, $valid_tally_names);
$timeframe  = get_enumerated_param($_GET, 'timeframe', null, array('curr_month', 'prev_month', 'all_time'));
$c_or_i     = get_enumerated_param($_GET, 'cori', null, array('cumulative', 'increments'));

// Initialize the graph before anything else.
// This makes use of the jpgraph cache if enabled.
// Argument to init_pages_graph is the cache timeout in minutes.
$graph = init_pages_graph(60);

$site_tallyboard = new TallyBoard( $tally_name, 'S' );

$now_timestamp = time();
$now_assoc = getdate($now_timestamp);

$curr_y = $now_assoc['year'];
$curr_m = $now_assoc['mon'];

switch ($timeframe)
{
    case 'curr_month':
        $start_timestamp = mktime( 0,0,0, $curr_m,   1, $curr_y );
        $end_timestamp   = mktime( 0,0,0, $curr_m+1, 1, $curr_y );
        $year_month      = strftime('%Y-%m', $start_timestamp);
        $where_clause    = "WHERE {year_month} = '$year_month'";
        $title_timeframe = strftime( _('%B %Y'), $now_timestamp );
        break;

    case 'prev_month':
        $start_timestamp = mktime( 0,0,0, $curr_m-1, 1, $curr_y );
        $end_timestamp   = mktime( 0,0,0, $curr_m,   1, $curr_y );
        $year_month      = strftime('%Y-%m', $start_timestamp);
        $where_clause    = "WHERE {year_month} = '$year_month'";
        $title_timeframe = strftime( _('%B %Y'), $start_timestamp );
        break;

    case 'all_time':
        $start_timestamp = 0;
        $end_timestamp   = mktime( 0,0,0, $curr_m+1, 1, $curr_y );
        $where_clause    = '';
        $title_timeframe = _('since stats began');
        break;

    default:
        die("bad 'timeframe' value: '$title_timeframe'");
}

switch ($c_or_i)
{
    case 'increments':
        $main_title = _('Pages Done Per Day');
        break;

    case 'cumulative':
        $main_title = _('Cumulative Pages Done');
        break;

    default:
        die("bad 'cori' value: '$c_or_i'");
}

// -----------------------------------------------------------------------------

$result = dpsql_query(
    select_from_site_past_tallies_and_goals(
        $tally_name,
        "SELECT {date}, tally_delta, goal",
        $where_clause,
        "",
        "ORDER BY timestamp",
        ""
    )
);

list($datax,$datay1,$datay2) = dpsql_fetch_columns($result);

if ( $c_or_i == 'cumulative' )
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


// Calculate a simple moving average for 'increments' graphs
if ($c_or_i == 'increments')
{
    $days_to_average = 21;

    // to ensure we have enough data to use, go back $days_to_average 
    // days before the start date
    $where_start_timestamp = $start_timestamp - ($days_to_average * 60*60*24);

    // Unless it's for all_time when start_timestamp==0. For this case we need
    // $days_to_average days after the timestamp is zero to ensure the average
    // is correct.
    if ( $start_timestamp == 0 )
        $where_start_timestamp = $start_timestamp + ($days_to_average * 60*60*24);

    $sql = "
        SELECT t1.timestamp,
            (SELECT round(sum(t2.tally_delta)/count(t2.tally_delta))
            FROM past_tallies as t2
            WHERE t2.holder_type = 'S'
                AND t2.holder_id = '1'
                AND t2.tally_name = '$tally_name'
                AND datediff(from_unixtime(t1.timestamp), from_unixtime(t2.timestamp)) between 0 and $days_to_average )
            as 'sma'
        FROM past_tallies as t1
        WHERE t1.holder_type = 'S'
            AND t1.tally_name = '$tally_name'
            AND t1.holder_id = '1'
            AND t1.timestamp > $where_start_timestamp
        ORDER BY t1.timestamp asc
        ";
    $res = mysqli_query(DPDatabase::get_connection(), $sql);

    // Get the earliest start date to use in our population of $moving_average
    // Because the results are sorted ascending by timestamp, the first one
    // is the one we want.
    $earliest_timestamp=null;

    // store the results in a date-based array we can use to populate the
    // graph's data array
    while( $result = mysqli_fetch_assoc($res) )
    {
        if($earliest_timestamp===null)
            $earliest_timestamp=$result["timestamp"];

        $average_lookup[strftime("%Y-%m-%d",$result["timestamp"])]=$result["sma"];
    }
    mysqli_free_result($res);

    // Don't start before the earliest timestamp
    $start_timestamp = max( $start_timestamp, $earliest_timestamp );
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

    // Ensure we don't have more SMA data points than we do $datax points.
    // This can happen if the statistics use to be kept but aren't any longer.
    for($index=count($moving_average)-count($datax); $index>0; $index--)
        array_pop($moving_average);
}
else
{
    $moving_average = null;
    $days_to_average = 0;
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
    sprintf(_("%d-day SMA"), $days_to_average),
    'daily',
    $c_or_i,
    "$main_title ($title_timeframe)"
);



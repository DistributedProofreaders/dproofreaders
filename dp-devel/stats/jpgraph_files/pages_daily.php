<?
$relPath="./../../pinc/";
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'connect.inc');
include_once('common.inc');
new dbConnect();

$now_timestamp = time();
$now_assoc = getdate($now_timestamp);

switch ( @$_GET['timeframe'] )
{
    case 'curr_month':
        $year  = $now_assoc['year'];
        $month = $now_assoc['mon'];
        $where = "WHERE year=$year AND month=$month";
        $title_timeframe = strftime( _('%B %Y'), $now_timestamp );
        break;

    case 'prev_month':
        $month_ago_timestamp =
            mktime( 0, 0, 0, $now_assoc['mon'] - 1, 1, $now_assoc['year'] );
        $month_ago_assoc = getdate( $month_ago_timestamp );
        $year  = $month_ago_assoc['year'];
        $month = $month_ago_assoc['mon'];
        $where = "WHERE year=$year AND month=$month";
        $title_timeframe = strftime( _('%B %Y'), $month_ago_timestamp );
        break;

    case 'all_time':
        $where = '';
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

$result = mysql_query("
    SELECT date, pages, dailygoal
    FROM pagestats
    $where
    ORDER BY date
");

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

draw_pages_graph(
    $datax,
    $datay1,
    $datay2,
    'daily',
    $cumulative_or_increments,
    "$main_title ($title_timeframe)",
    60
);

// vim: sw=4 ts=4 expandtab
?>

<?
$relPath="./../../pinc/";
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'connect.inc');
include_once('common.inc');
new dbConnect();

// For each hour in the $past interval,
// show the number of (distinct) users who had logged in
// (at least once) during the $preceding interval.

$past = $_GET['past'];
$preceding = $_GET['preceding'];

$seconds_per_day = 24 * 60 * 60;

switch ( $past )
{
	case 'year':
		$min_timestamp = time() - 366 * $seconds_per_day;
		$date_format = '%Y-%b-%d %H';
		break;

	case 'day':
		$min_timestamp = time() - $seconds_per_day;
		$date_format = '%d %H';
		break;

	default:
		die("bad value for 'past'");
}

switch ( $preceding )
{
	case 'hour':
		$title = "Number of users newly logged in each hour";
		$column_name = 'U_lasthour';
		$cache_timeout = 58;
		break;

	case 'day':
		$title = 'Number of users newly logged in over 24 hours';
		$column_name = 'U_day';
		$cache_timeout = 58;
		break;

	case 'week':
		$title = "Number of users newly logged in over 7 days";
		$column_name = 'U_week';
		$cache_timeout = 300;
		break;

	case 'fourweek':
		$title = "Number of users newly logged in over 28 days";
		$column_name = 'U_4wks';
		$cache_timeout = 900;
		break;

	default:
		die("bad value for 'preceding'");
}


///////////////////////////////////////////////////
//query db and put results into arrays

$result = mysql_query("
    SELECT DATE_FORMAT(FROM_UNIXTIME(time_stamp),'$date_format') as T, $column_name
    FROM user_active_log 
    WHERE time_stamp >= $min_timestamp
    ORDER BY time_stamp
");

list($datax,$datay) = dpsql_fetch_columns($result);

// calculate tick interval based on number of datapoints
// the data is hourly, there are 168 hours in a week
// once we have more than about 30 labels, the axis is getting too crowded
$mynumrows = count($datay);
if ($mynumrows < 30) {
	$tick = 1;              // one label per hour
} else if ($mynumrows < (30 * 168)) {
	$tick = 168;            // one label per week
} else if ($mynumrows < (30 * 168 * 4)) {
	$tick = 168 * 4;        // one label per 4 weeks (pseudo-month)
} else if ($mynumrows < (30 * 168 * 13)) {
	$tick = 168 * 13;       // one label per quarter
} else {
	$tick = 168 * 52;       // one label per year
}

draw_simple_bar_graph(
	$datax,
	$datay,
	$tick,
	$title,
	_('Fresh Logons'),
	640, 400,
	$cache_timeout
);

?>

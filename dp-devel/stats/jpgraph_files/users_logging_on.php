<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($relPath.'connect.inc');
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
		$date_format = 'Y-M-d H';
		$set_tick_interval = TRUE;
		break;

	case 'day':
		$min_timestamp = time() - $seconds_per_day;
		$date_format = 'd H';
		$set_tick_interval = FALSE;
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
    SELECT time_stamp, $column_name
    FROM user_active_log 
    WHERE time_stamp >= $min_timestamp
    ORDER BY time_stamp
");


$mynumrows = mysql_numrows($result);
        $count = 0;
        while ($count < $mynumrows) {
        $datay[$count] = mysql_result($result, $count, $column_name);
        $datax[$count] = date($date_format,mysql_result($result, $count,"time_stamp"));
            $count++;
        }

// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",$cache_timeout);
$graph->SetScale("textint");

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");
if ($set_tick_interval)
{
	// calculate tick interval based on number of datapoints
	// the data is hourly, there are 168 hours in a week
	// once we have more than about 30 labels, the axis is getting too crowded
	if ($mynumrows < (30 * 168)) {
		$tick = 168;            // one label per week
	} else if ($mynumrows < (30 * 168 * 4)) {
		$tick = 168 * 4;        // one label per 4 weeks (pseudo-month)
	} else if ($mynumrows < (30 * 168 * 13)) {
		$tick = 168 * 13;       // one label per quarter
	} else {
		$tick = 168 * 52;       // one label per year
	}
	$graph->xaxis->SetTextTickInterval($tick);
}

//Set Y axis
$graph->yaxis->title->Set(_('Fresh Logons'));
$graph->yaxis->SetTitleMargin(45);

//Set background to white
$graph->SetMarginColor('white');

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
//left, right , top, bottom

$graph->img->SetMargin(70,30,20,100);

// Create a bar pot
$bplot = new BarPlot($datay);
$bplot->SetColor("lightblue");
$graph->Add($bplot);

// Setup the title
$graph->title->Set($title);


$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);

// Display the graph
$graph->Stroke();

?>

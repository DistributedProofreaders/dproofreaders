<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($relPath.'connect.inc');
new dbConnect();

if ($_GET['range'] == 7 || $_GET['range'] == 14 || $_GET['range'] == 30 || $_GET['range'] == 60 || $_GET['range'] == 365 || $_GET['range'] == "all") {
	$range = $_GET['range'];
} else {
	$range = 30;
}

if ($range != "all") {
	$range = time() - ($range * 86400);
	$date_condition = "timestamp >= $range";
} else {
	$date_condition = "1";
}
$result = mysql_query("
	SELECT timestamp, tally_delta
	FROM past_tallies
	WHERE
		($date_condition)
		AND holder_type='T'
		AND holder_id={$_GET['tid']}
		AND tally_name='P'
	ORDER BY timestamp ASC
");

$i = 0;
while (list($timestamp, $tally_delta) = mysql_fetch_row($result)) {
	$datay[$i] = $tally_delta;
        $datax[$i] = date("n/j/Y", ($timestamp-86400));
        $i++;
}
$graph = new Graph(600,300,"auto",180);
$graph->SetScale("textint");

//set X axis
$graph->xaxis->SetTickLabels($datax);

	if ($_GET['range'] == 60) { $graph->xaxis->SetTextLabelInterval(2); }
	if ($_GET['range'] == 365) { $graph->xaxis->SetTextLabelInterval(15); $graph->xaxis->HideTicks(true); }
	if ($_GET['range'] == "all") { $graph->xaxis->SetTextLabelInterval(30); $graph->xaxis->HideTicks(true); }

$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");
//Set Y axis
$graph->yaxis->title->Set('Pages');
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
$graph->Add($bplot);

// Setup the title
$graph->title->Set("Team Statistics");


$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);

// Display the graph
$graph->Stroke();
?>


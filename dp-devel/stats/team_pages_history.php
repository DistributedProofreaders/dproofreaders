<?
include ("../../jpgraph-1.12.1/src/jpgraph.php");
include ("../../jpgraph-1.12.1/src/jpgraph_bar.php");
$relPath="./../pinc/";
include($relPath.'connect.inc');
new dbConnect();

if ($_GET['range'] == 7 || $_GET['range'] == 14 || $_GET['range'] == 30 || $_GET['range'] == 60 || $_GET['range'] == 365 || $_GET['range'] == "all") {
	$range = $_GET['range'];
} else {
	$range = 30;
}

if ($range != "all") {
	$range = time() - ($range * 86400);
	$result = mysql_query("SELECT * FROM user_teams_stats WHERE team_id = ".$_GET['tid']." && date_updated >= $range ORDER BY date_updated ASC");
} else {
	$result = mysql_query("SELECT * FROM user_teams_stats WHERE team_id = ".$_GET['tid']." ORDER BY date_updated ASC");
}

$i = 0;
while ($row = mysql_fetch_assoc($result)) {
	$datay[$i] = $row['daily_page_count'];
        $datax[$i] = date("n/j/Y", $row['date_updated']);
        $i++;
}
$graph = new Graph(600,300,"auto",180);
$graph->SetScale("textint");

//set X axis
$graph->xaxis->SetTickLabels($datax);
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


$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

// Display the graph
$graph->Stroke();
?>


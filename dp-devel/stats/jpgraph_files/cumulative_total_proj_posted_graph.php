<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($relPath.'connect.inc');
include_once($code_dir.'/stats/statestats.inc');
include_once($relPath.'gettext_setup.inc');

new dbConnect();

//Create cumulative projects posted per day graph for all days 
// since state stats started being recorded up to yesterday

$day = date("d");
$year  = date("Y");
$month = date("m");
$monthVar = date("F");
$today = $year."-".$month."-".$day;

$state_selector = "
		(state LIKE 'proj_submit%'
		OR state LIKE 'proj_correct%')";

//query db and put results into arrays
$result = mysql_query("SELECT sum(num_projects) as P, date FROM project_state_stats WHERE $state_selector GROUP BY DATE ORDER BY date ASC");
$i = 0;


while ($row = mysql_fetch_assoc($result)) {
	$datay1[$i] = $row['P'];
	$datax[$i] = $row['date'];
	$i++;
}

if (empty($datay1)) {
	$datay1[0] = 0;
}

// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",360);
$graph->SetScale("textint");
$graph->SetMarginColor('white'); //Set background to white
$graph->SetShadow(); //Add a drop shadow
$graph->img->SetMargin(70,30,20,100); //Adjust the margin a bit to make more room for titles left, right , top, bottom
$graph->img->SetAntiAliasing(); 


//Create the line plot
$lplot1 = new LinePlot($datay1);
$lplot1->SetColor("gold");
$lplot1->SetLegend(_("Total Projects Posted to PG"));
$lplot1->SetWeight(1);
$lplot1->SetFillColor("gold");


$graph->Add($lplot1); 

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");
// calculate tick interval based on number of datapoints
// the data is daily, there are 7 days in a week
// once we have more than about 30 labels, the axis is getting too crowded
if ($i < 30 ) {
        $tick = 1;            // one label per day
} else if ($i < (30 * 7)) {
        $tick = 7;            // one label per week
} else if ($i < (30 * 7 * 4)) {
        $tick = 7 * 4;        // one label per 4 weeks (pseudo-month)
} else if ($i < (30 * 7 * 13)) {
        $tick = 7 * 13;       // one label per quarter
} else {
        $tick = 7  * 52;       // one label per year
}
$graph->xaxis->SetTextTickInterval($tick);


//Set Y axis
$graph->yaxis->title->Set(_('Projects'));
$graph->yaxis->SetTitleMargin(45);


$graph->title->Set(_("Total Projects Posted to PG"));
$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->legend->SetFont($jpgraph_FF,$jpgraph_FS);

$graph->legend->Pos(0.5,0.5,"right" ,"top"); //Align the legend

// Display the graph
$graph->Stroke();
?>



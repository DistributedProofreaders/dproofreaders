<?
$relPath="./../../pinc/";
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($relPath.'connect.inc');
include_once($code_dir.'/stats/statestats.inc');
include_once($relPath.'gettext_setup.inc');
include_once('common.inc');

new dbConnect();

// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",360);
$graph->SetScale("textint");
$graph->SetMarginColor('white'); //Set background to white
$graph->SetShadow(); //Add a drop shadow
$graph->img->SetMargin(30,70,20,100); //Adjust the margin a bit to make more room for titles left, right , top, bottom
$graph->img->SetAntiAliasing(); 


$max_num_data = 0;

foreach ( array( 'created', 'proofed', 'PPd', 'posted' ) as $which )
{
	$psd = get_project_status_descriptor($which);

	//query db and put results into arrays
	$result = mysql_query("
		SELECT date, SUM(num_projects)
		FROM project_state_stats
		WHERE $psd->state_selector
		GROUP BY DATE
		ORDER BY date ASC
	");

	list($datax,$datay) = dpsql_fetch_columns($result);

	if (empty($datay)) {
		$datay[0] = 0;
	}

	if (count($datay) > $max_num_data) {
		$max_num_data = count($datay);
	}

	//Create the line plot
	$lplot =& new LinePlot($datay);
	$lplot->SetColor($psd->color);
	$lplot->SetLegend($psd->cumulative_title);
	$lplot->SetWeight(1);
	$lplot->SetFillColor($psd->color);
	$graph->Add($lplot); 
}

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");
// calculate tick interval based on number of datapoints
// the data is daily, there are 7 days in a week
// once we have more than about 30 labels, the axis is getting too crowded
if ($max_num_data < 30 ) {
        $tick = 1;            // one label per day
} else if ($max_num_data < (30 * 7)) {
        $tick = 7;            // one label per week
} else if ($max_num_data < (30 * 7 * 4)) {
        $tick = 7 * 4;        // one label per 4 weeks (pseudo-month)
} else if ($max_num_data < (30 * 7 * 13)) {
        $tick = 7 * 13;       // one label per quarter
} else {
        $tick = 7  * 52;       // one label per year
}
$graph->xaxis->SetTextTickInterval($tick);


//Set Y axis
//$graph->yaxis->title->Set(_('Projects'));
$graph->yaxis->SetPos("max");
$graph->yaxis->SetLabelSide(SIDE_RIGHT);
$graph->yaxis->SetTitleMargin(45);


$graph->title->Set(_("Total Projects Created, Proofed, PPd and Posted"));
$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->legend->SetFont($jpgraph_FF,$jpgraph_FS);

$graph->legend->Pos(0.15,0.1,"left" ,"top"); //Align the legend

// Display the graph
$graph->Stroke();
?>

<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');
include_once($relPath.'connect.inc');
include_once($code_dir.'/stats/statestats.inc');
include_once($relPath.'gettext_setup.inc');

new dbConnect();

//Create projects proofed per day graph for all known history


//query db and put results into arrays
$result = mysql_query("SELECT sum(num_projects) as PC, date  FROM project_state_stats WHERE  
				(state LIKE 'proj_submit%'
				OR state LIKE 'proj_correct%'
				OR state LIKE 'proj_post_second%')
			group by date ORDER BY date");
$mynumrows = mysql_numrows($result);


if ($mynumrows) {
	$base = mysql_result($result,0 , "PC");
	$datay1[0] = $base;
} else {
	$datay1[0] = 0;
}

$datax[0] = mysql_result($result, 0, "date");
$count = 1;


while ($count < $mynumrows) {
        $total = mysql_result($result, $count, "PC");
        $datay1[$count] = $total;
        $datay1[$count-1] = $total - $datay1[$count-1];
	if ($datay1[$count-1] < 0) $datay1[$count-1] = 0;
        $datax[$count] = mysql_result($result, $count, "date");
        $count++;
}
$datay1[$count - 1] = 0;
$datay1[$count] = 0;
$datay1[0] = 0;

// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",300);
$graph->SetScale("textint");
$graph->SetMarginColor('white'); //Set background to white
$graph->SetShadow(); //Add a drop shadow
$graph->img->SetMargin(70,30,20,100); //Adjust the margin a bit to make more room for titles left, right , top, bottom

//Create the bar plot
$bplot = new BarPlot($datay1);
$bplot->SetFillColor("silver");

$graph->Add($bplot); //Add the bar plot to the graph

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");
// calculate tick interval based on number of datapoints
// the data is daily, there are 7 days in a week
// once we have more than about 30 labels, the axis is getting too crowded
if ($mynumrows < 30 ) {
        $tick = 1;            // one label per day
} else if ($mynumrows < (30 * 7)) {
        $tick = 7;            // one label per week
} else if ($mynumrows < (30 * 7 * 4)) {
        $tick = 7 * 4;        // one label per 4 weeks (pseudo-month)
} else if ($mynumrows < (30 * 7 * 13)) {
        $tick = 7 * 13;       // one label per quarter
} else {
        $tick = 7  * 52;       // one label per year
}
$graph->xaxis->SetTextTickInterval($tick);

//Set Y axis
$graph->yaxis->title->Set(_("Projects"));
$graph->yaxis->SetTitleMargin(45);

$graph->title->Set(_("Projects Post-Processed Each Day Since Stats Began"));
$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);


// Display the graph

$graph->Stroke();



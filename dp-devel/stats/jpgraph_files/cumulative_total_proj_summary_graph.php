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

//Create cumulative projects created per day graph for all days 
// since state stats started being recorded up to yesterday

$day = date("d");
$year  = date("Y");
$month = date("m");
$monthVar = date("F");
$today = $year."-".$month."-".$day;

$state_selector = "
	(state NOT LIKE 'proj_new%')
";

//query db and put results into arrays
$resultCreated = mysql_query("SELECT sum(num_projects) as P, date FROM project_state_stats WHERE $state_selector GROUP BY DATE ORDER BY date ASC");


$state_selector = "
	(state LIKE 'proj_submit%'
		OR state LIKE 'proj_correct%'
		OR state LIKE 'proj_post%')
";



//query db and put results into arrays
$resultProofed = mysql_query("SELECT sum(num_projects) as P, date FROM project_state_stats WHERE $state_selector GROUP BY DATE ORDER BY date ASC");


$state_selector = "
	(state LIKE 'proj_submit%'
		OR state LIKE 'proj_correct%'
		OR state LIKE 'proj_post_second%')
";


//query db and put results into arrays
$resultPPd = mysql_query("SELECT sum(num_projects) as P, date FROM project_state_stats WHERE $state_selector GROUP BY DATE ORDER BY date ASC");


$state_selector = "
	(state LIKE 'proj_submit%'
		OR state LIKE 'proj_correct%')
";

//query db and put results into arrays
$resultPosted = mysql_query("SELECT sum(num_projects) as P, date FROM project_state_stats WHERE $state_selector GROUP BY DATE ORDER BY date ASC");

$i = 0;


while ($row = mysql_fetch_assoc($resultCreated)) {
	$datay1[$i] = $row['P'];
	$datax[$i] = $row['date'];
	$i++;
}

$i = 0

while ($row = mysql_fetch_assoc($resultProofed)) {
	$datay2[$i] = $row['P'];
	$i++;
}

$i = 0

while ($row = mysql_fetch_assoc($resultPPd)) {
	$datay3[$i] = $row['P'];
	$i++;
}

$i = 0

while ($row = mysql_fetch_assoc($resultPosted)) {
	$datay3[$i] = $row['P'];
	$i++;
}



if (empty($datay1)) {
	$datay1[0] = 0;
}

if (empty($datay2)) {
	$datay2[0] = 0;
}

if (empty($datay3)) {
	$datay3[0] = 0;
}

if (empty($datay4)) {
	$datay4[0] = 0;
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
$lplot1->SetColor("green");
$lplot1->SetLegend(_("Total Projects Created"));
$lplot1->SetWeight(1);
$lplot1->SetFillColor("green");


//Create the line plot
$lplot2 = new LinePlot($datay2);
$lplot2->SetColor("blue");
$lplot2->SetLegend(_("Total Projects Proofed"));
$lplot2->SetWeight(1);
$lplot2->SetFillColor("blue");

//Create the line plot
$lplot3 = new LinePlot($datay3);
$lplot3->SetColor("silver");
$lplot3->SetLegend(_("Total Projects PPd"));
$lplot3->SetWeight(1);
$lplot3->SetFillColor("silver");

//Create the line plot
$lplot4 = new LinePlot($datay4);
$lplot4->SetColor("gold");
$lplot4->SetLegend(_("Total Projects Posted"));
$lplot4->SetWeight(1);
$lplot4->SetFillColor("gold");



$graph->Add($lplot1); 
$graph->Add($lplot2); 
$graph->Add($lplot3); 
$graph->Add($lplot4); 

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");
$graph->xaxis->SetTextTickInterval(91.25);

//Set Y axis
$graph->yaxis->title->Set(_('Projects'));
$graph->yaxis->SetTitleMargin(45);


$graph->title->Set(_("Total Projects Created"));
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$graph->legend->Pos(0.5,0.5,"right" ,"top"); //Align the legend

// Display the graph
$graph->Stroke();
?>



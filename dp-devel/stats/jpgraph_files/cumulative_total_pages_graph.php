<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($relPath.'connect.inc');
new dbConnect();

//Create pages per day graph for all pages done since stats started
$day = date("d");
$year  = date("Y");
$month = date("m");
$monthVar = date("F");
$today = $year."-".$month."-".$day;

//query db and put results into arrays
$result = mysql_query("SELECT pages,date, dailygoal FROM pagestats where pages > 0 ORDER BY date ASC");
$i = 0;
$p = 0;
$g = 0;

while ($row = mysql_fetch_assoc($result)) {
	if ($row['date'] < $today) {$datay1[$i] = $row['pages']+$p;}
	$datay2[$i] = $row['dailygoal']+$g;
	$datax[$i] = $row['date'];
	$p = $p+$row['pages'];
	$g = $g+$row['dailygoal'];
	$i++;
}

if (empty($datay1)) {
	$datay1[0] = 0;
}

// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",1);
$graph->SetScale("textint");
$graph->SetMarginColor('white'); //Set background to white
$graph->SetShadow(); //Add a drop shadow
$graph->img->SetMargin(70,30,20,100); //Adjust the margin a bit to make more room for titles left, right , top, bottom

//Create the bar plot
$lplot1 = new LinePlot($datay1);
$lplot1->SetColor("blue");
$lplot1->SetWeight(1);
$lplot1->SetLegend("Total Pages Completed");
$lplot1->SetFillColor("blue");

//Create the linear goal plot
$lplot2=new LinePlot($datay2);
$lplot2->SetColor("limegreen");
$lplot2->SetWeight(2);
$lplot2->SetLegend("Cumulative Goal");

$graph->Add($lplot1); //Add the linear goal plot to the graph
$graph->Add($lplot2); //Add the bar pages completed plot to the graph

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");
$graph->xaxis->SetTextTickInterval(91.25);

//Set Y axis
$graph->yaxis->title->Set('Pages');
$graph->yaxis->SetTitleMargin(45);


$graph->title->Set("Cumulative Pages Completed Since Stats Started");
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$graph->legend->Pos(0.5,0.5,"right" ,"top"); //Align the legend

// Display the graph
$graph->Stroke();
?>


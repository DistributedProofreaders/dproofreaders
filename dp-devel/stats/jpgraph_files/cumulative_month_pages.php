<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');
include_once($relPath.'connect.inc');
new dbConnect();

$today = getdate();
if ($today['mday'] == 1 && ($today['hours'] >=0 && $today ['hours'] <= 3)) {
	if (isset($_GET['ignore_archive_graph']) && $_GET['ignore_archive_graph'] == 1) {
		$todaysTimeStamp = time() - 86400;
		echo "BACK!!";
	} else {
		if (!file_exists($dynstats_dir."/graph_archive/cumulative_month_pages/".date("Fy",time()-86400).".png")) {
			header("Location: ".$code_dir."/stats/jpgraph_files/cumulative_month_pages.php?ignore_archive_graph=1");
		} else {
			$todaysTimeStamp = time();
		}
	}
} else {
	$todaysTimeStamp = time();
}

//Create pages per day graph for current month
$day = date("d", $todaysTimeStamp);
$year  = date("Y", $todaysTimeStamp);
$month = date("m", $todaysTimeStamp);
$monthVar = date("F", $todaysTimeStamp);
$today = $year."-".$month."-".$day;

//query db and put results into arrays
$result = mysql_query("SELECT pages,date,dailygoal FROM pagestats WHERE month = '$month' AND year = '$year' ORDER BY date ASC");
$i = 0;
$p = 0;
$g = 0;

while ($row = mysql_fetch_assoc($result)) {
	if (substr($row['date'], -2) < $day) { $datay1[$i] = $row['pages']+$p; }
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
$graph = new Graph(640,400,"auto",60);
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
$lplot2->SetLegend("Monthly Goal");

$graph->Add($lplot1); //Add the linear goal plot to the graph
$graph->Add($lplot2); //Add the bar pages completed plot to the graph

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");

//Set Y axis
$graph->yaxis->title->Set('Pages');
$graph->yaxis->SetTitleMargin(45);

$graph->title->Set("Cumulative Pages Completed for $monthVar $year");
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$graph->legend->Pos(0.05,0.5,"right" ,"top"); //Align the legend

// Display the graph
if (isset($_GET['ignore_archive_graph']) && $_GET['ignore_archive_graph'] == 1) {
	$archiveGraphPath = $dynstats_dir."/graph_archive/cumulative_month_pages/".date("Fy",time()-86400).".png";
	$graph ->Stroke($archiveGraphPath);
	sleep(5);
	header("Location: ".$code_url."/stats/stats_central.php");
} else {
	$graph->Stroke();
}
?>


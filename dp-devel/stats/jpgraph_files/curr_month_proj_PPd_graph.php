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

$today = getdate();
if ($today['mday'] == 1 && ($today['hours'] >=0 && $today ['hours'] <= 3)) {
	if (isset($_GET['ignore_archive_graph']) && $_GET['ignore_archive_graph'] == 1) {
		$todaysTimeStamp = time() - 86400;
		echo "BACK!!";
	} else {
		if (!file_exists($code_dir."/stats/graph_archive/curr_month_proj_PPd/".date("Fy",time()-86400).".png")) {
			header("Location: ".$code_url."/stats/jpgraph_files/curr_month_proj_PPd_graph.php?ignore_archive_graph=1");
		} else {
			$todaysTimeStamp = time();
		}
	}
} else {
	$todaysTimeStamp = time();
}

//Create projects created per day graph for current month
$year  = date("Y");
$month = date("m");


// number of days this month - note that unlike project_state_stats, 
// which gets a row added for each new day just after midnight,
// pagestats is prepopulated with rows for the whole month
$result = mysql_query("SELECT max(day) as maxday FROM pagestats WHERE month = '$month' AND year = '$year'");
$maxday = mysql_result($result, 0, "maxday");


//query db and put results into arrays
$result = mysql_query("SELECT sum(num_projects) as PC, day as PC FROM project_state_stats WHERE month = '$month' AND year = '$year' 
				AND (state LIKE 'proj_submit%'
				OR state LIKE 'proj_correct%'
				OR state LIKE 'proj_post_second%') group by day ORDER BY day");
$mynumrows = mysql_numrows($result);


if ($mynumrows) {
	$base = mysql_result($result,0 , "PC");
	$datay1[0] = $base;
} else {
	$datay1[0] = 0;
}
$datax[0] = 1;
$count = 1;


while ($count < $maxday) {
	if ($count < $mynumrows) {
		$total = mysql_result($result, $count, "PC");
	       	$datay1[$count] = $total;
		$datay1[$count-1] = $total - $datay1[$count-1];
	} else {
		$datay1[$count-1] = 0;
		}
        $datax[$count] = $count + 1;
        $count++;
}

// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",300);
$graph->SetScale("textint");
$graph->SetMarginColor('white'); //Set background to white
$graph->SetShadow(); //Add a drop shadow
$graph->img->SetMargin(70,30,20,100); //Adjust the margin a bit to make more room for titles left, right , top, bottom

//Create the bar plot
$bplot = new BarPlot($datay1);
$bplot->SetLegend(_("Projects PPd"));
$bplot->SetFillColor("silver");

$graph->Add($bplot); //Add the bar plot to the graph

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");

//Set Y axis
$graph->yaxis->title->Set(_("Projects"));
$graph->yaxis->SetTitleMargin(45);

$graph->title->Set(_("Projects PPd Per Day for Current Month"));
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$graph->legend->Pos(0.05,0.5,"right" ,"top"); //Align the legend

// Display the graph
if (isset($_GET['ignore_archive_graph']) && $_GET['ignore_archive_graph'] == 1) {
	$archiveGraphPath = $code_dir."/stats/graph_archive/curr_month_proj_PPd/".date("Fy",time()-86400).".png";
	$graph ->Stroke($archiveGraphPath);
	sleep(5);
	header("Location: ".$code_url."/stats/stats_central.php");
} else {
	$graph->Stroke();
}
?>



<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');
include_once($relPath.'connect.inc');
include_once($code_dir.'/stats/statestats.inc');
include_once($relPath.'gettext_setup.inc');

new dbConnect();

// Create "projects Xed per day" graph for current month

$which = $_GET['which'];

switch ( $which )
{
	case 'created':
		$state_selector = "
			state NOT LIKE 'proj_new%'
		";
		$color = 'green';
		$legend = _('Projects Created');
		$title = _('Cumulative Projects Created for');
		break;

	case 'proofed':
		$state_selector = "
			state LIKE 'proj_submit%'
			OR state LIKE 'proj_correct%'
			OR state LIKE 'proj_post%'
		";
		$color = 'blue';
		$legend = _('Projects Proofed');
		$title = _('Cumulative Projects Proofed for');
		break;

	case 'PPd':
		$state_selector = "
			state LIKE 'proj_submit%'
			OR state LIKE 'proj_correct%'
			OR state LIKE 'proj_post_second%'
		";
		$color = 'silver';
		$legend = _('Projects PPd');
		$title = _('Cumulative Projects PPd for');
		break;

	case 'posted':
		$state_selector = "
			state LIKE 'proj_submit%'
			OR state LIKE 'proj_correct%'
		";
		$color = 'gold';
		$legend = _('Projects Posted');
		$title = _('Cumulative Projects Posted to PG for');
		break;

	default:
		die("bad value for 'which'");
}

$today = getdate();
if ($today['mday'] == 1 && ($today['hours'] >=0 && $today ['hours'] <= 3)) {
	if (isset($_GET['ignore_archive_graph']) && $_GET['ignore_archive_graph'] == 1) {
		$todaysTimeStamp = time() - 86400;
		echo "BACK!!";
	} else {
		if (!file_exists($dynstats_dir."/graph_archive/cumulative_month_proj_$which/".date("Fy",time()-86400).".png")) {
			header("Location: ".$code_url."/stats/jpgraph_files/cumulative_month_proj.php?which=$which&ignore_archive_graph=1");
		} else {
			$todaysTimeStamp = time();
		}
	}
} else {
	$todaysTimeStamp = time();
}

$day = date("d", $todaysTimeStamp);
$year  = date("Y", $todaysTimeStamp);
$month = date("m", $todaysTimeStamp);
$monthVar = _(date("F", $todaysTimeStamp));
$today = $year."-".$month."-".$day;


// number of days this month - note that unlike project_state_stats, 
// which gets a row added for each new day just after midnight,
// pagestats is prepopulated with rows for the whole month
$result = mysql_query("SELECT max(day) as maxday FROM pagestats WHERE month = '$month' AND year = '$year'");
$maxday = mysql_result($result, 0, "maxday");

//query db and put results into arrays
$result = mysql_query("SELECT sum(num_projects) as P, day FROM project_state_stats WHERE month = '$month' AND year = '$year' 
				AND ($state_selector)
				group by day ORDER BY day");
$mynumrows = mysql_numrows($result);


$i = 0;
$p = 0;

// get base level, total at beginning of 1st day of month
	// snapshot is taken just after midnight,
	// so day = 1 has total at beginning of month

$row = mysql_fetch_assoc($result);
$base = $row['P'];

while ($row = mysql_fetch_assoc($result)) {
 	$datay1[$i] = $row['P'] - $base; 
	// snapshot is taken just *after* midnight,
	// so day7 minus day1 is number done over first six days of month
	$datax[$i] = $row['day'] - 1;
	$i++;
}

while ($i < $maxday) {
      $datay1[$i] = "";
      $datax[$i] = $i + 1;
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

//Create the line
$lplot1 = new LinePlot($datay1);
$lplot1->SetColor($color);
$lplot1->SetWeight(1);
$lplot1->SetLegend($legend);

// only add colour to the part we have data for
$lplot1->AddArea(0,$mynumrows,LP_AREA_FILLED,$color);


$graph->Add($lplot1); //Add the line plot to the graph


//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");

//Set Y axis
$graph->yaxis->title->Set(_("Projects"));
$graph->yaxis->SetTitleMargin(45);

$graph->title->Set("$title $monthVar $year");
$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->legend->SetFont($jpgraph_FF,$jpgraph_FS);

$graph->legend->Pos(0.05,0.5,"right" ,"top"); //Align the legend

// Display the graph
if (isset($_GET['ignore_archive_graph']) && $_GET['ignore_archive_graph'] == 1) {
	$archiveGraphPath = $dynstats_dir."/graph_archive/cumulative_month_proj_$which/".date("Fy",time()-86400).".png";
	$graph ->Stroke($archiveGraphPath);
	sleep(5);
	header("Location: ".$code_url."/stats/stats_central.php");
} else {
	$graph->Stroke();
}
?>



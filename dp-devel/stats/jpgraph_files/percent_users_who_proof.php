<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');
include_once($relPath.'connect.inc');
include_once($relPath.'page_tally.php');
new dbConnect();


///////////////////////////////////////////////////
// For each month in which someone joined,
// get the number who joined,
// and the number of those who have proofed at least one page.
//
$result = mysql_query("
	SELECT
		FROM_UNIXTIME(date_created, '%Y-%m')
		  AS month,
		COUNT(*)
		  AS num_who_joined,
		SUM($user_P_page_tally_column > 0)
		  AS num_who_proofed
	FROM $users_table_with_tallies
	GROUP BY month
	ORDER BY month
");

// If there was a month when nobody joined,
// then the results will not include a row for that month.
// This may lead to a misleading graph,
// depending on its style.

while ( $row = mysql_fetch_object($result) )
{
        $datax[]  = $row->month;
        $data1y[] = 100 *  $row->num_who_proofed / $row->num_who_joined;
}


// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",900);
$graph->SetScale("textint",0,100);

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");

//Set Y axis
$graph->yaxis->title->Set('% of newly Joined Users who Proofed');
$graph->yaxis->SetTitleMargin(45);

//Set background to white
$graph->SetMarginColor('white');

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
//left, right , top, bottom

$graph->img->SetMargin(70,30,20,100);


 // Create the line plot
$l1plot = new LinePlot ($data1y);
$l1plot ->SetFillColor ("lightseagreen");


// ...and add it to the graPH
$graph->Add( $l1plot);


// Setup the title
$graph->title->Set("Percentage of New Users Who Went on to Proof By Month");


$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);

$graph->legend->Pos(0.15,0.1,"left" ,"top"); //Align the legend

// Display the graph
$graph->Stroke();


?>


<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');
include_once($relPath.'connect.inc');
new dbConnect();


///////////////////////////////////////////////////
//Numbers of users logging on in last hour, day, week and 28 days
//query db and put results into arrays
$result1 = mysql_query("
	SELECT FROM_UNIXTIME(date_created, '%Y-%m') as Month, count(*) as Joined FROM users 
	GROUP BY Month
	ORDER BY Month
");

$result2 = mysql_query("
	SELECT FROM_UNIXTIME(date_created, '%Y-%m') as Month, count(*) as EverProofed FROM users 
	WHERE pagescompleted > 0 
	GROUP BY Month
	ORDER BY Month
");


$mynumrows = mysql_numrows($result1);
        $count = 0;
        while ($count < $mynumrows) {
        $data1y[$count] = 100 *  mysql_result($result2, $count,"EverProofed") / mysql_result($result1, $count,"Joined");
        $datax[$count] = mysql_result($result1, $count,"Month");
            $count++;
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


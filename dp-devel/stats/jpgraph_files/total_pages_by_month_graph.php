<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($relPath.'connect.inc');
new dbConnect();


///////////////////////////////////////////////////
//Total pages by month since beginning of stats
//query db and put results into arrays
$result = mysql_query("SELECT sum(pages) as sumpages,year,month FROM pagestats GROUP BY year, month ORDER BY
year ASC, month ASC");
$mynumrows = mysql_numrows($result);
        $count = 0;
        while ($count < $mynumrows) {
        $datay[$count] = mysql_result($result, $count,"sumpages");
        $datax[$count] = mysql_result($result, $count,"year"). "-".mysql_result($result, $count,"month")   ;
            $count++;
        }

// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",1440);
$graph->SetScale("textint");

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");
// calculate tick interval based on number of datapoints
// the data is monthly
// once we have more than about 30 labels, the axis is getting too crowded
if ($mynumrows < 30 ) {
        $tick = 1;            // one label per month
} else if ($mynumrows < (30 * 3)) {
        $tick = 3;            // one label per quarter
} else {
        $tick = 12;           // one label per year
}
$graph->xaxis->SetTextTickInterval($tick);

//Set Y axis
$graph->yaxis->title->Set('Pages');
$graph->yaxis->SetTitleMargin(45);

//Set background to white
$graph->SetMarginColor('white');

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
//left, right , top, bottom

$graph->img->SetMargin(70,30,20,100);

// Create a bar pot
$bplot = new BarPlot($datay);
$bplot->SetColor("blue");
$graph->Add($bplot);

// Setup the title
$graph->title->Set("Pages Done Each Month Since the Beginning of Statistics Collection");


$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);

// Display the graph
$graph->Stroke();


?>


<?
include ("jpgraph-1.12.1/src/jpgraph.php");
include ("jpgraph-1.12.1/src/jpgraph_bar.php");
$relPath="c/pinc/";
include($relPath.'connect.inc');
new dbConnect();


///////////////////////////////////////////////////
//Total pages per day since beginning of stats
//query db and put results into arrays
$result = mysql_query("SELECT pages,date,year,month, day FROM pagestats ORDER BY
year ASC, month ASC, day ASC");
$mynumrows = mysql_numrows($result);
        $count = 0;
        while ($count < $mynumrows) { 
        $datay[$count] = mysql_result($result, $count,"pages");
        $datax[$count] = mysql_result($result, $count,"date");
            $count++;
        }

// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",1);
$graph->SetScale("textint");

//set X axis
$graph->xaxis->SetTickLabels($datax);
// Only draw labels on every 2nd tick mark 
//$graph->xaxis->SetTextLabelInterval(91.25); 
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");
$graph->xaxis->SetTextTickInterval(91.25);
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
$graph->Add($bplot);

// Setup the title
$graph->title->Set("Pages Done Per Day Since the Beginning of Statistics Collection");


$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
        
// Display the graph 
$graph->Stroke();


?>


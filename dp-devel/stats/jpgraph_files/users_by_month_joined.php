<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($relPath.'connect.inc');
new dbConnect();


///////////////////////////////////////////////////
//Numbers of users logging on in last hour, day, week and 28 days
//query db and put results into arrays
$result = mysql_query("
	SELECT FROM_UNIXTIME(date_created, '%Y-%m') as Date, count(*) as Num FROM users 
	GROUP BY FROM_UNIXTIME(date_created, '%Y-%m')
	ORDER BY FROM_UNIXTIME(date_created, '%Y-%m')
";



$mynumrows = mysql_numrows($result);
        $count = 0;
        while ($count < $mynumrows) {
        $datay[$count] = mysql_result($result, $count,"Num");
        $datax[$count] = mysql_result($result, $count,"Date");
            $count++;
        }

// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",900);
$graph->SetScale("textint");

//set X axis
$graph->xaxis->SetTickLabels($datax);
// Only draw labels on every 2nd tick mark
//$graph->xaxis->SetTextLabelInterval(91.25);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");
$graph->xaxis->SetTextTickInterval(91.25);
//Set Y axis
$graph->yaxis->title->Set('New Users Per Month');
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
$graph->title->Set("Number of new users who joined each month");


$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

// Display the graph
$graph->Stroke();


?>


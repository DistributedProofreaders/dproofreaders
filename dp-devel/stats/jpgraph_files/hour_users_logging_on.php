<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($relPath.'connect.inc');
new dbConnect();


///////////////////////////////////////////////////
//Numbers of users logging on in last hour
//query db and put results into arrays
$result = mysql_query("SELECT U_lasthour, time_stamp,year,month, day, hour FROM user_active_log 
WHERE time_stamp > 1071896580 
ORDER BY time_stamp");

// over an hour

$mynumrows = mysql_numrows($result);
        $count = 0;
        while ($count < $mynumrows) {
        $datay[$count] = mysql_result($result, $count,"U_lasthour");
        $datax[$count] = date('Y-M-d H',mysql_result($result, $count,"time_stamp"));
            $count++;
        }

// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",58);
$graph->SetScale("textint");

//set X axis
$graph->xaxis->SetTickLabels($datax);
// Only draw labels on every 2nd tick mark
//$graph->xaxis->SetTextLabelInterval(91.25);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");
$graph->xaxis->SetTextTickInterval(91.25);
//Set Y axis
$graph->yaxis->title->Set('Fresh Logons');
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
$graph->title->Set("Number of users newly logged in each hour");


$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

// Display the graph
$graph->Stroke();


?>


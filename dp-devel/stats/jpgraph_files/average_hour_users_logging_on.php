<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($relPath.'connect.inc');
new dbConnect();

$title = _('Average number of users newly logged in each hour');
$cache_timeout = 58;

///////////////////////////////////////////////////
//Numbers of users logging on in each hopur of  last 24

$startfrom = time() - 86400;


//query db and put results into arrays


$result = mysql_query("SELECT hour,AVG(U_lasthour) AS users 
FROM user_active_log 
GROUP BY hour 
ORDER BY hour");

// over an hour

$mynumrows = mysql_numrows($result);
        $count = 0;
        while ($count < $mynumrows) {
        $datay[$count] = mysql_result($result, $count,"users");
        $datax[$count] = date('H',strtotime("+".mysql_result($result, $count,"hour")." hour",0));
            $count++;
        }

// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",$cache_timeout);
$graph->SetScale("textint");

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");

//Set Y axis
$graph->yaxis->title->Set(_('Fresh Logons'));
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
$graph->title->Set($title);


$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);

// Display the graph
$graph->Stroke();


?>


<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');
include_once($relPath.'connect.inc');
new dbConnect();

//Create pages per day graph for current month
$year  = date("Y");
$month = date("m");

//query db and put results into arrays
$result = mysql_query("SELECT pages,date,dailygoal FROM pagestats WHERE month = '$month' AND year = '$year'");
$mynumrows = mysql_numrows($result);
        $count = 0;
        while ($count < $mynumrows) {
        $datay1[$count] = mysql_result($result, $count, "pages");
        $datay2[$count] = mysql_result($result, $count, "dailygoal");
        $datax[$count] = mysql_result($result, $count, "date");
            $count++;
        }

// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",1);
$graph->SetScale("textint");
$graph->SetMarginColor('white'); //Set background to white
$graph->SetShadow(); //Add a drop shadow
$graph->img->SetMargin(70,30,20,100); //Adjust the margin a bit to make more room for titles left, right , top, bottom

//Create the bar plot
$bplot = new BarPlot($datay1);
$bplot->SetLegend("Pages Completed");

//Create the linear goal plot
$lplot=new LinePlot($datay2);
$lplot->SetColor("lime");
$lplot->SetWeight(1);
$lplot->SetLegend("Daily Goal");

$graph->Add($lplot); //Add the linear goal plot to the graph
$graph->Add($bplot); //Add the bar pages completed plot to the graph

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");

//Set Y axis
$graph->yaxis->title->Set('Pages');
$graph->yaxis->SetTitleMargin(45);

$graph->title->Set("Pages Done Per Day for Current Month");
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$graph->legend->Pos(0.05,0.5,"right" ,"top"); //Align the legend

// Display the graph
$graph->Stroke();
?>


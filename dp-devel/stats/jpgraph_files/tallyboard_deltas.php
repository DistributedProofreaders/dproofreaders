<?
$relPath="./../../pinc/";
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($relPath.'TallyBoard.inc');
include_once($relPath.'connect.inc');
new dbConnect();

$tally_name  = array_get( $_GET, 'tally_name',  '' );
$holder_type = array_get( $_GET, 'holder_type', '' );
$holder_id   = array_get( $_GET, 'holder_id',   '' );
$days_back   = array_get( $_GET, 'days_back', '30' );

if ( $tally_name  == '' ) die( "tally_name is empty" );
if ( $holder_type == '' ) die( "holder_type is empty" );
if ( $holder_id   == '' ) die( "holder_id is empty" );
if ( $days_back   == '' ) die( "days_back is empty" );

if ($days_back == "all")
{
    $min_timestamp = 0;
}
else
{
    $min_timestamp = time() - ($days_back * 86400);
}

$tallyboard = new TallyBoard( $tally_name, $holder_type );
$deltas = $tallyboard->get_deltas( $holder_id, $min_timestamp );

$datax = array();
$datay = array();
foreach ( $deltas as $timestamp => $tally_delta )
{
    $datax[] = date("n/j/Y", ($timestamp-86400));
    $datay[] = $tally_delta;
}

$graph = new Graph(600,300,"auto",180);
$graph->SetScale("textint");

//set X axis
$graph->xaxis->SetTickLabels($datax);

if ( $days_back >= 700 )
{
    $x_tli = 30;
}
else if ( $days_back >= 365 )
{
    $x_tli = 15;
}
else if ( $days_back >= 60)
{
    $x_tli = 2;
}
else
{
    $x_tli = 1;
}
$graph->xaxis->SetTextLabelInterval( $x_tli );

if ( $days_back >= 365 )
{
    $graph->xaxis->HideTicks(true);
}

$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set("");
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
$graph->title->Set("Pages Completed per Day");


$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);

// Display the graph
$graph->Stroke();

// vim: sw=4 ts=4 expandtab
?>


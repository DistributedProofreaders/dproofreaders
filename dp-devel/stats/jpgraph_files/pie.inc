<?
// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph=new PieGraph(640,400,"auto",58);

//Set background to white
$graph->SetMarginColor('white');

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
//left, right , top, bottom

$graph->img->SetMargin(70,30,20,100);

// Add the plot
$plot=new PiePlot($y);
$plot->SetSize(175);
$plot->SetCenter(0.33);
$plot->SetLegends($x);
$plot->SetLabelType(PIE_VALUE_ABS);
$plot->value->SetFont($jpgraph_FF,$jpgraph_FS);
$plot->value->SetFormat('%d');
$plot->value->Show();

$graph->Add($plot);

// Setup the title
$graph->title->Set($title);

$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->legend->SetFont($jpgraph_FF,$jpgraph_FS);

// Display the graph
$graph->Stroke();

?>

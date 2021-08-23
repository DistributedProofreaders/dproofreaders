<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'../stats/jpgraph_files/common.inc');
include_once($relPath.'page_tally.inc'); // I think?

# Get start of current month as unixtime
$m_start = mktime(0, 0, 0, date('m'), 1, date('Y'));

# We want the "PP pages goal" to be equal to the current month's F2 actuals
$page_offset = 0;
$site_stats = get_site_page_tally_summary("F2");
$pp_page_goal = $site_stats->curr_month_actual + $page_offset;

// Start with creating the Graph, this enables the use of the cache
// where possible
$width=120;
$height=200;
$cache_timeout=20; # in minutes
$graph = new Graph($width,$height,"auto",$cache_timeout);

# Get the total pages for projects that have posted
$page_res = mysqli_query(DPDatabase::get_connection(), "
    SELECT 
    SUM(n_pages)
    FROM projects
    WHERE state='proj_submit_pgposted'
    AND modifieddate >= $m_start
");

$row = mysqli_fetch_row($page_res);
$pp_pages_total = $row[0];

// calculate the goal percent as long as $pp_page_goal isn't zero
if($pp_page_goal != 0)
    $goal_percent = ceil(($pp_pages_total / $pp_page_goal) *100);
else
    $goal_percent = 0;

// Some graph variables
$datax = array(_("F2"),_("PP"));
$datay = array($pp_page_goal,$pp_pages_total);
$title = _("MTD Pages");
$x_title = "PP = " . $goal_percent . "% of F2";

$graph->SetScale("textlin",0,max($datay)*1.1);//padscaling
$graph->img->SetAntiAliasing();

// Set margin to standard DP background color
$graph->SetMarginColor("#e0e8dd");

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
// left, right, top, bottom
$graph->img->SetMargin(50,16,30,48);

// Set title
$graph->title->Set($title);
$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);

// Set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->title->Set($x_title);
$graph->xaxis->title->SetFont($jpgraph_FF,FS_NORMAL,7);
$graph->xaxis->title->SetMargin(4);
$graph->xaxis->HideTicks();

// Set Y axis
//$graph->yaxis->title->Set($y_title);
//$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->yaxis->HideTicks();

// Create a bar plot
$plot = new BarPlot($datay);
// If PP is higher than F2, use greens, else reds
if ( $pp_pages_total > $pp_page_goal ) {
    $plot->SetFillGradient("darkgreen","lightgreen",GRAD_VER);
} else {
    $plot->SetFillGradient("darkred","lightred",GRAD_VER);
}

// Add display of values at top of bars
$plot->value->Show();
$plot->value->SetColor("black");
$plot->value->SetFont($jpgraph_FF,FS_NORMAL,8);
$plot->value->SetAngle(0);
$plot->value->SetFormat("%d");

$graph->Add($plot);

// Display the graph
$graph->Stroke();

// vim: sw=4 ts=4 expandtab

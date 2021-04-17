<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'stages.inc');
include_once('common.inc');

// This image shows how many days it would take to complete all pages in
// the current round at the current rate. The current rate is calculated
// as the average number of pages completed in that round over the previous
// 7 days. Because this graph uses trend information, the graph can change
// day-to-day based on pages completed in each round.
//
// see also: http://www.pgdp.net/wiki/Round_backlog_graphs

// Start with creating the Graph, this enables the use of the cache
// where possisble
$width = 300;
$height = 200;
$cache_timeout = 59; // in minutes
$graph = new Graph($width, $height, get_image_cache_filename(), $cache_timeout);

// Pull all interested phases, primarily all the rounds and PP
$interested_phases = array_keys($Round_for_round_id_);

// Pull the stats data out of the database
$stats = get_round_backlog_stats($interested_phases);

// get the total of all phases
$stats_total = array_sum($stats);

// If this is a new system there won't be any stats so don't divide by zero
if ($stats_total == 0) {
    dpgraph_error(_("No pages found."), $width, $height);
}


// Get page saveAsDone trend information
$holder_id = 1;
$today = getdate();
foreach ($stats as $phase => $pages) {
    $tallyboard = new TallyBoard($phase, 'S');

    $pages_last_week = $tallyboard->get_delta_sum($holder_id,
        mktime(0, 0, 0, $today['mon'], $today['mday'] - 7, $today['year']),
        mktime(0, 0, 0, $today['mon'], $today['mday'], $today['year']));

    $avg_pages_per_day[$phase] = $pages_last_week / 7;

    // calculate the number of days to complete at the current rate
    if ($avg_pages_per_day[$phase]) {
        $stats[$phase] = $pages / $avg_pages_per_day[$phase];
    } else {
        $stats[$phase] = 0;
    }
}

// calculate the goal percent as 100 / number_of_phases
$goal_percent = ceil(100 / count($stats));

// colors
$barColors = [];
$barColorDefault = "#EEEEEE";
$barColorAboveGoal = "#FF484F";
$goalColor = "#0000FF";

$days_total = array_sum($stats);
if ($days_total == 0) {
    $days_total = 1;
}

// calculate the percentage of work remaining in each round
// and the color for each bar
foreach ($stats as $phase => $num_days) {
    $stats_percentage[$phase] = ceil(($num_days / $days_total) * 100);
    if ($stats_percentage[$phase] > $goal_percent) {
        $barColors[] = $barColorAboveGoal;
    } else {
        $barColors[] = $barColorDefault;
    }
}

// Some graph variables
$datax = array_keys($stats);
$datay = array_values($stats);
$title = _("Days to finish all pages in Rounds");
// TRANSLATORS: This string is the title of a graph with very little room.
//              Try to keep the translation the same length as the English text.
$x_title = _("Help is most needed in the red rounds");

// scale from 0 to 110% of max
$graph->SetScale("textint", 0, max($datay) * 1.1);
$graph->graph_theme = null;
$graph->img->SetAntiAliasing();

// Set background to white
$graph->SetMarginColor('white');

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
// left, right, top, bottom
$graph->img->SetMargin(50, 20, 30, 60);

// Set title
$graph->title->Set($title);
$graph->title->SetFont($jpgraph_FF, FS_BOLD, 10);

// Set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->title->Set($x_title);
$graph->xaxis->title->SetFont($jpgraph_FF, $jpgraph_FS);
$graph->xaxis->title->SetMargin(10);
$graph->xaxis->HideTicks();

// Set Y axis
$graph->yaxis->HideTicks();

// Create a bar plot
$plot = new BarPlot($datay);
$plot->SetFillColor($barColors);
$graph->Add($plot);

// Add display of values at top of bars
$plot->value->Show();
$plot->value->SetColor("black");
$plot->value->SetFont($jpgraph_FF, $jpgraph_FS, 8);
$plot->value->SetAngle(0);
$plot->value->SetFormat("%d");

// Display the graph
$graph->Stroke();

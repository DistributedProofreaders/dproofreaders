<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'graph_data.inc');
include_once($relPath.'slim_header.inc');

// This image shows how many days it would take to complete all pages in
// the current round at the current rate. The current rate is calculated
// as the average number of pages completed in that round over the previous
// 7 days. Because this graph uses trend information, the graph can change
// day-to-day based on pages completed in each round.
//
// see also: http://www.pgdp.net/wiki/Round_backlog_graphs

$width = 300;
$height = 200;

// Pull all interested phases, primarily all the rounds and PP
$interested_phases = array_keys($Round_for_round_id_);

// Pull the stats data out of the database
$stats = get_round_backlog_stats($interested_phases);

// get the total of all phases
$stats_total = array_sum($stats);

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

// If this is a new system there won't be any stats so don't divide by zero
if ($stats_total == 0) {
    $title = _("No pages found.");
}

$js_data = '$(function(){barChart("round_backlog_days",' . json_encode([
    "title" => $title,
    "axisLeft" => true,
    "barColors" => $barColors,
    "data" => [
        $x_title => [
            "x" => $datax,
            "y" => $datay,
        ],
    ],
    "width" => $width,
    "height" => $height,
    "barBorder" => true,
    "bottomLegend" => $x_title,
    "yAxisTickCount" => 5,
]) . ');});';


slim_header($title, [
    "body_attributes" => "style='margin: 0'",
    "js_files" => get_graph_js_files(),
    "js_data" => $js_data,

]);

echo "<div id='round_backlog_days' style='width:" . $width . "px;height:" . $height . "px;'></div>";

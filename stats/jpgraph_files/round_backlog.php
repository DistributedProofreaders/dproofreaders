<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'graph_data.inc');
include_once($relPath.'slim_header.inc');
include_once('common.inc');


// This image shows the total number of pages remaining in each round.
// The red bars show the rounds that have more than the average number
// of pages (calculated as the total number of pages over the number of
// rounds). Because this image shows the total number of pages in each
// round, it will not noticeably change day-to-day unless something
// drastic occurs in a given round.
//
// see also: http://www.pgdp.net/wiki/Round_backlog_graphs

$width = 300;
$height = 200;

// Pull all interested phases, primarily all the rounds and PP
$interested_phases = array_keys($Round_for_round_id_);
$interested_phases[] = "PP";

// Pull the stats data out of the database
$stats = get_round_backlog_stats($interested_phases);

// get the total of all phases
$stats_total = array_sum($stats);

// calculate the goal percent as 100 / number_of_phases
$goal_percent = ceil(100 / count($stats));

// colors
$barColors = [];
$barColorDefault = "#EEEEEE";
$barColorAboveGoal = "#FF484F";
$goalColor = "#0000FF";

// calculate the percentage of work remaining in each round
// and the color for each bar
foreach ($stats as $phase => $num_pages) {
    $stats_percentage[$phase] = ceil(($num_pages / $stats_total) * 100);
    if ($stats_percentage[$phase] > $goal_percent) {
        $barColors[] = $barColorAboveGoal;
    } else {
        $barColors[] = $barColorDefault;
    }
}

// Some graph variables
$datax = array_keys($stats);
$datay = array_values($stats);
$title = _("Pages remaining in Rounds");
// TRANSLATORS: This string is the title of a graph with very little room.
//              Try to keep the translation the same length as the English text.
$x_title = _("Help is most needed in the red rounds");

// If this is a new system there won't be any stats so don't divide by zero
if ($stats_total == 0) {
    $title = _("No pages found.");
}

$js_data = '$(function(){barChart("round_backlog",' . json_encode([
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
]) . ');});';


slim_header($title, [
    "body_attributes" => "style='margin: 0'",
    "js_files" => get_graph_js_files(),
    "js_data" => $js_data,

]);

echo "<div id='round_backlog' style='width:" . $width . "px;height:" . $height . "px;'></div>";

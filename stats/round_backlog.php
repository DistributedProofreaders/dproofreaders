<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'graph_data.inc');
include_once($relPath.'slim_header.inc');


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

function _get_round_backlog_data()
{
    // Pull all interested phases, primarily all the rounds and PP
    $interested_phases = Rounds::get_ids();
    $interested_phases[] = "PP";

    // Pull the stats data out of the database
    return get_round_backlog_stats($interested_phases);
}

// cache backlog data for 1 day
$stats = query_graph_cache("_get_round_backlog_data", [], 60 * 60 * 24);

// get the total of all phases
$stats_total = array_sum($stats);

// calculate the goal percent as 100 / number_of_phases
$goal_percent = ceil(100 / max(1, count($stats)));

// colors
$barColors = [];
$barColorDefault = "graph-normal-series";
$barColorAboveGoal = "graph-above-goal";

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

$graphs = [
    ["barLineGraph", "round_backlog", [
        "title" => $title,
        "barColors" => $barColors,
        "data" => [
            $x_title => [
                "x" => $datax,
                "y" => $datay,
            ],
        ],
        "width" => $width,
        "height" => $height,
        "bottomLegend" => $x_title,
        "yAxisTickCount" => 5,
    ]],
];

slim_header($title, [
    "body_attributes" => "style='margin: 0;overflow: hidden'",
    "js_files" => get_graph_js_files(),
    "js_data" => build_svg_graph_inits($graphs),
]);

echo "<div id='round_backlog' style='width:" . $width . "px;height:" . $height . "px;'></div>";

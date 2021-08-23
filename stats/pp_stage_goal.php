<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'graph_data.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'page_tally.inc');

// Get start of current month as unixtime
$m_start = mktime(0, 0, 0, date('m'), 1, date('Y'));

// We want the "PP pages goal" to be equal to the current month's F2 actuals
$page_offset = 0;
$site_stats = get_site_page_tally_summary("F2");
$pp_page_goal = $site_stats->curr_month_actual + $page_offset;

// Start with creating the Graph, this enables the use of the cache
// where possible
$width = 120;
$height = 200;

// Get the total pages for projects that have posted
$page_res = DPDatabase::query(sprintf("
    SELECT 
    SUM(n_pages)
    FROM projects
    WHERE state='proj_submit_pgposted'
    AND modifieddate >= %d
", $m_start));

$row = mysqli_fetch_row($page_res);
$pp_pages_total = $row[0];

// calculate the goal percent as long as $pp_page_goal isn't zero
if ($pp_page_goal != 0) {
    $goal_percent = ceil(($pp_pages_total / $pp_page_goal) * 100);
} else {
    $goal_percent = 0;
}

// Some graph variables
$datax = [_("F2"), _("PP")];
$datay = [$pp_page_goal, $pp_pages_total];
$title = _("MTD Pages");
$x_title = "PP = " . $goal_percent . "% of F2";

// Adjust the margin a bit to make more room for titles
// left, right, top, bottom
// $graph->img->SetMargin(50,16,30,48);

// If PP is higher than F2, use greens, else reds
if ($pp_pages_total > $pp_page_goal) {
    $barColors = ["darkgreen"];
} else {
    $barColors = ["darkred"];
}

$js_data = '$(function(){barChart("pp_stage_goal",' . json_encode([
    "title" => $title,
    "data" => [
        $x_title => [
            "x" => $datax,
            "y" => $datay,
        ],
    ],
    "barColors" => $barColors,
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

echo "<div id='pp_stage_goal' style='width:" . $width . "px;height:" . $height . "px;'></div>";

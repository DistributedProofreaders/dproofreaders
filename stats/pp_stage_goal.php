<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'graph_data.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'page_tally.inc');

// Get start of current month as unixtime
$m_start = mktime(0, 0, 0, date('m'), 1, date('Y'));

// We want the "PP pages goal" to be equal to the current month's last round before PP (F2) actuals
$page_offset = 0;
$round_before_PP = null;
foreach ($Round_for_round_id_ as $id => $round) {
    if ($round->id == "PP") {
        break;
    }
    $round_before_PP = $round->id;
}
$site_stats = get_site_page_tally_summary($round_before_PP);
$pp_page_goal = $site_stats->curr_month_actual + $page_offset;

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
$datax = [$round_before_PP, "PP"];
$datay = [$pp_page_goal, $pp_pages_total];
$title = _("MTD Pages");
$x_title = "PP = " . $goal_percent . "% of $round_before_PP";

// If PP is higher than round_before_PP, use greens, else reds
if ($pp_pages_total > $pp_page_goal) {
    $barColors = ["darkgreen"];
} else {
    $barColors = ["darkred"];
}

$width = 160;
$height = 200;

$graphs = [
    ["barLineGraph", "pp_stage_goal", [
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
        "yAxisTickCount" => 5,
    ]],
];

slim_header($title, [
    "body_attributes" => "style='margin: 0'",
    "js_files" => get_graph_js_files(),
    "js_data" => build_svg_graph_inits($graphs),

]);

echo "<div id='pp_stage_goal' style='width:" . $width . "px;height:" . $height . "px;'></div>";

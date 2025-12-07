<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'graph_data.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'page_tally.inc');

// We want the "PP pages goal" to be equal to the current month's last round before PP (F2) actuals
$round_before_PP = null;
foreach (Activities::get_all() as $activity) {
    if ($activity->id == "PP") {
        break;
    }
    $round_before_PP = $activity->id;
}
$site_stats = get_site_page_tally_summary($round_before_PP);
$pp_page_goal = $site_stats->curr_month_actual;

function _get_pages_posted_data($start_timestamp)
{
    $page_res = DPDatabase::query(sprintf(
        "
        SELECT SUM(n_pages)
        FROM projects
        WHERE state='%s'
            AND modifieddate >= %d
        ",
        PROJ_SUBMIT_PG_POSTED,
        $start_timestamp
    ));

    $row = mysqli_fetch_row($page_res);
    return $row[0];
}

// Get the total pages for projects that have posted in current month
$start_timestamp = mktime(0, 0, 0, (int)date('m'), 1, (int)date('Y'));
// cache pages posted data for 1 hour
$pp_pages_total = query_graph_cache("_get_pages_posted_data", [$start_timestamp], 60 * 60);

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

if ($pp_pages_total > $pp_page_goal) {
    $barColors = ["graph-normal-series", "graph-normal-series"];
} else {
    $barColors = ["graph-above-goal", "graph-above-goal"];
}

$width = 160;
$height = 200;

$graphs = [
    ["barLineGraph", "pp_stage_goal", [
        "title" => $title,
        "downloadLabel" => _("Download Chart"),
        "data" => [
            $x_title => [
                "x" => $datax,
                "y" => $datay,
            ],
        ],
        "barColors" => $barColors,
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

echo "<div id='pp_stage_goal' style='width:" . $width . "px;height:" . $height . "px;'></div>";

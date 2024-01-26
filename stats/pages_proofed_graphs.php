<?php
$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'graph_data.inc');

require_login();

$valid_tally_names = array_keys(get_page_tally_names());
$tally_name = get_enumerated_param($_GET, 'tally_name', null, $valid_tally_names);

// cache the graphs for as long as is reasonable
$now = new DateTimeImmutable("now");
$tomorrow_midnight = new DateTimeImmutable("tomorrow");
$first_next_month = new DateTimeImmutable("first day of next month");
$first_next_week = new DateTimeImmutable("next Sunday");

$title = sprintf(_('Graphs for Pages Saved-as-Done in Round %s'), $tally_name);
$graphs = [
    [
        "barLineGraph",
        "pages_daily_increments_curr_month",
        query_graph_cache("pages_daily", [$tally_name, "increments", "curr_month"], $tomorrow_midnight->format("U")),
    ],
    [
        "barLineGraph",
        "pages_daily_cumulative_curr_month",
        query_graph_cache("pages_daily", [$tally_name, "cumulative", "curr_month"], $tomorrow_midnight->format("U")),
    ],
    [
        "barLineGraph",
        "pages_daily_increments_prev_month",
        query_graph_cache("pages_daily", [$tally_name, "increments", "prev_month"], $first_next_month->format("U")),
    ],
    [
        "barLineGraph",
        "pages_daily_increments_all_time",
        query_graph_cache("pages_daily", [$tally_name, "increments", "all_time"], $first_next_week->format("U")),
    ],
    [
        "barLineGraph",
        "pages_daily_cumulative_all_time",
        query_graph_cache("pages_daily", [$tally_name, "cumulative", "all_time"], $first_next_week->format("U")),
    ],
    [
        "barLineGraph",
        "total_pages_by_month_graph",
        query_graph_cache("total_pages_by_month_graph", [$tally_name], $first_next_month->format("U")),
    ],
];

output_header($title, SHOW_STATSBAR, [
    "js_files" => get_graph_js_files(),
    "js_data" => build_svg_graph_inits($graphs),
]);

echo <<<DEBUG
        <!--          Now: {$now->format("c")}
        Tomorrow midnight: {$tomorrow_midnight->format("c")}
         First next month: {$first_next_month->format("c")}
          First next week: {$first_next_week->format("c")}
        -->
    DEBUG;

echo "<h1>$title</h1>";

echo "<div style='max-width: 640px'>";
foreach ($graphs as [$type, $id]) {
    echo "<div id='$id' style='max-height: 400px'></div><hr class='graph-divider'>";
}
echo "</div>";

<?php
$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()
include_once($relPath.'graph_data.inc');

require_login();

$valid_tally_names = array_keys(get_page_tally_names());
$tally_name = get_enumerated_param($_GET, 'tally_name', null, $valid_tally_names);

$title = sprintf(_('Graphs for Pages Saved-as-Done in Round %s'), $tally_name);
$charts = [
    ["id" => "pages_daily_increments_curr_month", "config" => pages_daily($tally_name, "increments", "curr_month")],
    ["id" => "pages_daily_cumulative_curr_month", "config" => pages_daily($tally_name, "cumulative", "curr_month")],
    ["id" => "pages_daily_increments_prev_month", "config" => pages_daily($tally_name, "increments", "prev_month")],
    ["id" => "pages_daily_increments_all_time", "config" => pages_daily($tally_name, "increments", "all_time")],
    ["id" => "pages_daily_cumulative_all_time", "config" => pages_daily($tally_name, "cumulative", "all_time")],
    ["id" => "total_pages_by_month_graph", "config" => total_pages_by_month_graph($tally_name)],
];

$js_data = '$(function(){';
foreach ($charts as $chart) {
    $js_data .= 'barChart("' . $chart["id"] . '", ' . json_encode($chart["config"]) . ');';
}
$js_data .= '});';

output_header($title, SHOW_STATSBAR, [
    "js_files" => get_graph_js_files(),
    "js_data" => $js_data,
]);
echo "<h1>$title</h1>";

echo "<div style='max-width: 640px'>";
foreach ($charts as $chart) {
    echo "<div id='" . $chart["id"] . "' style='max-height: 400px'></div><hr>";
}
echo "</div>";

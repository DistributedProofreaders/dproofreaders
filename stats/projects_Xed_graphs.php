<?php
$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()
include_once($relPath.'graph_data.inc');

require_login();

$which = get_enumerated_param($_GET, 'which', null, $project_status_descriptors);

$psd = get_project_status_descriptor($which);

$charts = [
    ["id" => "curr_month_proj", "config" => curr_month_proj($which)],
    ["id" => "cumulative_month_proj", "config" => cumulative_month_proj($which)],
    ["id" => "total_proj_graph", "config" => total_proj_graph($which)],
    ["id" => "cumulative_total_proj_graph", "config" => cumulative_total_proj_graph($which)],
];

$js_data = '$(function(){';
foreach ($charts as $chart) {
    $js_data .= 'barChart("' . $chart["id"] . '", ' . json_encode($chart["config"]) . ');';
}
$js_data .= '});';

output_header($psd->graphs_title, SHOW_STATSBAR, [
    "js_files" => get_graph_js_files(),
    "js_data" => $js_data,
]);

echo "<h1>$psd->graphs_title</h1>";

echo "<div style='max-width: 640px'>";
foreach ($charts as $chart) {
    echo "<div id='" . $chart["id"] . "' style='max-height: 400px'></div><hr>";
}
echo "</div>";

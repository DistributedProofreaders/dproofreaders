<?php
$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'graph_data.inc');

require_login();

$which = get_enumerated_param($_GET, 'which', null, get_project_status_descriptors());

$psd = get_project_status_descriptor($which);

$graphs = [
    ["barLineGraph", "curr_month_proj", curr_month_proj($which)],
    ["barLineGraph", "cumulative_month_proj", cumulative_month_proj($which)],
    ["barLineGraph", "total_proj_graph", total_proj_graph($which)],
    ["barLineGraph", "cumulative_total_proj_graph", cumulative_total_proj_graph($which)],
];

output_header($psd->graphs_title, SHOW_STATSBAR, [
    "js_files" => get_graph_js_files(),
    "js_data" => build_svg_graph_inits($graphs),
]);

echo "<h1>$psd->graphs_title</h1>";

echo "<div style='max-width: 640px'>";
foreach ($graphs as [$type, $id]) {
    echo "<div id='$id' style='max-height: 400px'></div><hr class='graph-divider'>";
}
echo "</div>";

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

$images = [
    "jpgraph_files/pages_daily.php?tally_name=$tally_name&amp;cori=increments&amp;timeframe=curr_month",
    "jpgraph_files/pages_daily.php?tally_name=$tally_name&amp;cori=cumulative&amp;timeframe=curr_month",
    "jpgraph_files/pages_daily.php?tally_name=$tally_name&amp;cori=increments&amp;timeframe=prev_month",
    "jpgraph_files/pages_daily.php?tally_name=$tally_name&amp;cori=increments&amp;timeframe=all_time",
    "jpgraph_files/pages_daily.php?tally_name=$tally_name&amp;cori=cumulative&amp;timeframe=all_time",
];

foreach ($images as $image) {
    echo "<img style='max-width: 100%' src='$image'></br>\n";
}

echo "<div style='max-width: 640px'>";
foreach ($charts as $chart) {
    echo "<div id='" . $chart["id"] . "' style='max-height: 400px'></div><hr>";
}
echo "</div>";

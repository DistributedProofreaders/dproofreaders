<?php
$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'graph_data.inc');

require_login();

$title = _("Miscellaneous User Statistics");
$charts = [
    ["id" => "average_hour_users_logging_on", "config" => average_hour_users_logging_on()],
    ["id" => "users_by_language", "config" => users_by_language()],
    ["id" => "users_by_country", "config" => users_by_country()],
    ["id" => "new_users", "config" => new_users("month")],
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

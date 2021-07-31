<?php
$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'graph_data.inc');

require_login();

$title = _("Miscellaneous User Statistics");
$charts = [
    ["id" => "average_hour_users_logging_on", "url" => null, "config" => average_hour_users_logging_on()],
    ["id" => "users_by_language", "url" => "jpgraph_files/users_by_language.php", "config" => null],
    ["id" => "users_by_country", "url" => "jpgraph_files/users_by_country.php", "config" => null],
    ["id" => "new_users", "url" => null, "config" => new_users("month")],
];

$js_data = '$(function(){';
foreach ($charts as $chart) {
    if (!is_null($chart["config"])) {
        $js_data .= 'barChart("' . $chart["id"] . '", ' . json_encode($chart["config"]) . ');';
    }
}
$js_data .= '});';

output_header($title, SHOW_STATSBAR, [
    "js_files" => get_graph_js_files(),
    "js_data" => $js_data,
]);
echo "<h1>$title</h1>";

echo "<div style='max-width: 640px'>";
foreach ($charts as $chart) {
    if (!is_null($chart["url"])) {
        echo "<img style='max-width: 100%' src='" . $chart["url"] . "'><br>\n";
    } else {
        echo "<div id='" . $chart["id"] . "' style='max-height: 400px'></div><hr>";
    }
}
echo "</div>";

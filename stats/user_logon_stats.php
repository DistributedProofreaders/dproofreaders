<?php
$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'graph_data.inc');

require_login();

$title = _("User Logon Statistics");
$charts = [
    ["id" => "past_day_preceding_hour", "type" => "barChart", "past" => "day", "preceding" => "hour"],
    ["id" => "past_year_preceding_hour", "type" => "stackedAreaChart", "past" => "year", "preceding" => "hour"],
    ["id" => "past_year_preceding_day", "type" => "stackedAreaChart", "past" => "year", "preceding" => "day"],
    ["id" => "past_year_preceding_week", "type" => "stackedAreaChart", "past" => "year", "preceding" => "week"],
    ["id" => "past_year_preceding_fourweek", "type" => "stackedAreaChart", "past" => "year", "preceding" => "fourweek"],
];

$js_data = '$(function(){';
foreach ($charts as $chart) {
    $js_data .= $chart["type"] . '("' . $chart["id"] . '", ' . json_encode(user_logging_on($chart["past"], $chart["preceding"])) . ');';
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

<?php
$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'graph_data.inc');

require_login();

$title = _("User Logon Statistics");
$graphs = [
    ["barLineGraph", "past_day_preceding_hour", user_logging_on("day", "hour")],
    ["stackedAreaGraph", "past_year_preceding_hour", user_logging_on("year", "hour")],
    ["stackedAreaGraph", "past_year_preceding_day", user_logging_on("year", "day")],
    ["stackedAreaGraph", "past_year_preceding_week", user_logging_on("year", "week")],
    ["stackedAreaGraph", "past_year_preceding_fourweek", user_logging_on("year", "fourweek")],
];

output_header($title, SHOW_STATSBAR, [
    "js_files" => get_graph_js_files(),
    "js_data" => build_svg_graph_inits($graphs),
]);
echo "<h1>$title</h1>";

echo "<div style='max-width: 640px'>";
foreach ($graphs as [$type, $id]) {
    echo "<div id='$id' style='max-height: 400px'></div><hr class='graph-divider'>";
}
echo "</div>";

<?php
$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'graph_data.inc');

require_login();

$title = _("User Logon Statistics");
$graphs = [
    ["barChart", "past_day_preceding_hour", user_logging_on("day", "hour")],
    ["stackedAreaChart", "past_year_preceding_hour", user_logging_on("year", "hour")],
    ["stackedAreaChart", "past_year_preceding_day", user_logging_on("year", "day")],
    ["stackedAreaChart", "past_year_preceding_week", user_logging_on("year", "week")],
    ["stackedAreaChart", "past_year_preceding_fourweek", user_logging_on("year", "fourweek")],
];

output_header($title, SHOW_STATSBAR, [
    "js_files" => get_graph_js_files(),
    "js_data" => build_svg_graph_inits($graphs),
]);
echo "<h1>$title</h1>";

echo "<div style='max-width: 640px'>";
foreach ($graphs as [$type, $id]) {
    echo "<div id='$id' style='max-height: 400px'></div><hr>";
}
echo "</div>";

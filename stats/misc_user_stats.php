<?php
$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'graph_data.inc');

require_login();

$title = _("Miscellaneous User Statistics");
$graphs = [
    ["barLineGraph", "average_hour_users_logging_on", average_hour_users_logging_on()],
    ["barLineGraph", "users_by_language", users_by_language()],
    ["barLineGraph", "users_by_country", users_by_country()],
    ["barLineGraph", "new_users", new_users("month")],
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

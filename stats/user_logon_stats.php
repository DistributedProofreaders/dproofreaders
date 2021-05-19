<?php
$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'graph_data.inc');

require_login();

$title = _("User Logon Statistics");
output_header($title, SHOW_STATSBAR, [
    "js_files" => get_graph_js_files(),
    "js_data" => '$(function(){
  stackedAreaChart("past_year_preceding_hour", ' . json_encode(user_logging_on("year", "hour")) . ');
  stackedAreaChart("past_year_preceding_day", ' . json_encode(user_logging_on("year", "day")) . ');
  stackedAreaChart("past_year_preceding_week", ' . json_encode(user_logging_on("year", "week")) . ');
  stackedAreaChart("past_year_preceding_fourweek", ' . json_encode(user_logging_on("year", "fourweek")) . ');
});',
]);
echo "<h1>$title</h1>";

echo "<div id='past_day_preceding_hour' style='max-width: 640px'></div>";
echo "<div id='past_year_preceding_hour' style='max-width: 640px'></div>";
echo "<div id='past_year_preceding_day' style='max-width: 640px'></div>";
echo "<div id='past_year_preceding_week' style='max-width: 640px'></div>";
echo "<div id='past_year_preceding_fourweek' style='max-width: 640px'></div>";
echo "<div id='test' style='max-width: 640px'></div>";

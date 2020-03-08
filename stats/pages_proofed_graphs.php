<?php
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()

$valid_tally_names = array_keys(get_page_tally_names());
$tally_name   = get_enumerated_param($_GET, 'tally_name', null, $valid_tally_names);

$title = sprintf( _('Graphs for Pages Saved-as-Done in Round %s'), $tally_name );

output_header($title);
echo "<h1>$title</h1>";

echo "<img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=increments&timeframe=curr_month\"><br>";
echo "<img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=cumulative&timeframe=curr_month\"><br>";
echo "<img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=increments&timeframe=prev_month\"><br>";
echo "<img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=increments&timeframe=all_time\"><br>";
echo "<img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=cumulative&timeframe=all_time\"><br>";
echo "<img src=\"jpgraph_files/total_pages_by_month_graph.php?tally_name=$tally_name\"><br>";

// vim: sw=4 ts=4 expandtab

<?php
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

$valid_tally_names = array_keys($page_tally_names);
$tally_name   = get_enumerated_param($_GET, 'tally_name', null, $valid_tally_names);

$title = sprintf( _('Graphs for Pages Saved-as-Done in Round %s'), $tally_name );

theme($title,'header');
echo "<center><h1><i>$title</i></h1></center>";

echo "<br><br>";

echo "<center><img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=increments&timeframe=curr_month\"></center><br>";
echo "<center><img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=cumulative&timeframe=curr_month\"></center><br>";
echo "<center><img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=increments&timeframe=prev_month\"></center><br>";
echo "<center><img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=increments&timeframe=all_time\"></center><br>";
echo "<center><img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=cumulative&timeframe=all_time\"></center><br>";
echo "<center><img src=\"jpgraph_files/total_pages_by_month_graph.php?tally_name=$tally_name\"></center><br>";

theme('','footer');

// vim: sw=4 ts=4 expandtab

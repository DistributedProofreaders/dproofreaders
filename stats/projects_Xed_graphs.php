<?php
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()

$which = get_enumerated_param($_GET, 'which', null, $project_status_descriptors);

$psd = get_project_status_descriptor($which);

output_header($psd->graphs_title);

echo "<h1>$psd->graphs_title</h1>";

echo "<img src=\"jpgraph_files/curr_month_proj.php?which=$which\"><br>";
echo "<img src=\"jpgraph_files/cumulative_month_proj.php?which=$which\"><br>";
echo "<img src=\"jpgraph_files/total_proj_graph.php?which=$which\"><br>";
echo "<img src=\"jpgraph_files/cumulative_total_proj_graph.php?which=$which\"><br>";

// vim: sw=4 ts=4 expandtab

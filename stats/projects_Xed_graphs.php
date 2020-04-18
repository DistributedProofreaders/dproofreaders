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

$images = [
    "jpgraph_files/curr_month_proj.php?which=$which",
    "jpgraph_files/cumulative_month_proj.php?which=$which",
    "jpgraph_files/total_proj_graph.php?which=$which",
    "jpgraph_files/cumulative_total_proj_graph.php?which=$which",
];

foreach($images as $image)
{
    echo "<img style='max-width: 100%' src='$image'><br>\n";
}


// vim: sw=4 ts=4 expandtab

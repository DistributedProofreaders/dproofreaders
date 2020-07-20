<?php
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once('../../stats/jpgraph_files/common.inc');

require_login();

$projectid = get_projectID_param($_GET, "projectid");

// data for this graph is generated in show_wordcheck_page_stats.php
$data_filename = sys_get_temp_dir() . "/$projectid-graph_pages_per_number_of_flags.dat";
$graph_pages_per_number_of_flags = unserialize(file_get_contents($data_filename));
unlink($data_filename);

draw_simple_bar_graph(
   init_simple_bar_graph(600, 300, 0.001), // ignore any cached graph image
   $graph_pages_per_number_of_flags["graph_x"],
   $graph_pages_per_number_of_flags["graph_y"],
   ceil(count($graph_pages_per_number_of_flags["graph_x"])/40),
   _("Number of flags on a page"),
   _("Pages with that many flags")
);

// vim: sw=4 ts=4 expandtab

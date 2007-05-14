<?
$relPath="../../pinc/";
include_once('../../stats/jpgraph_files/common.inc');

$projectid = $_GET["projectid"];

// data for this graph is generated in show_wordcheck_page_stats.php

draw_simple_bar_graph(
   $_SESSION["graph_flags_per_page"][$projectid]["graph_x"],
   $_SESSION["graph_flags_per_page"][$projectid]["graph_y"],
   ceil(count($_SESSION["graph_flags_per_page"][$projectid]["graph_x"])/40),
   _("Flagged words per page"),
   _("Flags"),
   600,300,
   -1
);

// unsetting graph_flags_per_page variable in the session
// to prevent it from getting large
// consider keeping this data if calling this
// image multiple times is needed in future code changes
unset($_SESSION["graph_flags_per_page"][$projectid]);
?>

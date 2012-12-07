<?php
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once('../../stats/jpgraph_files/common.inc');

$projectid = validate_projectID("projectid", @$_GET["projectid"]);

// data for this graph is generated in show_wordcheck_page_stats.php

draw_simple_bar_graph(
   init_simple_bar_graph(600, 300, -1),
   $_SESSION["graph_flags_per_page"][$projectid]["graph_x"],
   $_SESSION["graph_flags_per_page"][$projectid]["graph_y"],
   ceil(count($_SESSION["graph_flags_per_page"][$projectid]["graph_x"])/40),
   _("Flagged words per page"),
   _("Flags")
);

// unsetting graph_flags_per_page variable in the session
// to prevent it from getting large
// consider keeping this data if calling this
// image multiple times is needed in future code changes
unset($_SESSION["graph_flags_per_page"][$projectid]);
?>

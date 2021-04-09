<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once('common.inc');

// Initialize the graph before anything else.
// This makes use of the jpgraph cache if enabled.
// Last argument to init_pie_graph is the cache timeout in minutes.
$graph = init_pie_graph(640, 400, 58);

$res=mysqli_query(DPDatabase::get_connection(), "SELECT IFNULL(LEFT(u_intlang,2),'') AS intlang,COUNT(*) AS num FROM users GROUP BY intlang ORDER BY num DESC");

$x=array(); $y=array();

while($r=mysqli_fetch_assoc($res)) {
    array_push($x,(
        $r['intlang']?
            dgettext("iso_639",eng_name($r['intlang'])):
            _("Browser default")
        )." (%d)"
    );
    array_push($y,$r['num']);
}

$title=_("Number of users per user interface language");

draw_pie_graph(
    $graph,
    $x,
    $y,
    $title
);


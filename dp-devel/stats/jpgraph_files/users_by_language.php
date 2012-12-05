<?php
$relPath="./../../pinc/";
include_once($relPath.'connect.inc');
include_once($relPath.'gettext_setup.inc');
include_once('common.inc');

// Initialize the graph before anything else.
// This makes use of the jpgraph cache if enabled.
// Last argument to init_pie_graph is the cache timeout in minutes.
$graph = init_pie_graph(640, 400, 58);

new dbConnect();

$res=mysql_query("SELECT IFNULL(LEFT(u_intlang,2),'') AS intlang,COUNT(*) AS num FROM users GROUP BY intlang ORDER BY num DESC");

$x=array(); $y=array();

while($r=mysql_fetch_assoc($res)) {
    array_push($x,(
        $r['intlang']?
            dgettext("iso_639",eng_name($r['intlang'])):
            _("Browser Default")
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

// vim: sw=4 ts=4 expandtab
?>

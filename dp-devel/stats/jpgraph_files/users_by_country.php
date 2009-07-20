<?php
$relPath="./../../pinc/";
include_once($relPath.'connect.inc');
include_once('common.inc');

// Initialize the graph before anything else.
// This makes use of the jpgraph cache if enabled.
// Last argument to init_pie_graph is the cache timeout in minutes.
$graph = init_pie_graph(640, 400, 58);

new dbConnect();

$res=mysql_query("
    SELECT
        SUBSTRING_INDEX(email,'.',-1) AS domain,
        COUNT(*) AS num
    FROM users
    WHERE email LIKE '%@%.%'
    GROUP BY domain
    ORDER BY num DESC
");

$x=array(); $y=array();

while($r=mysql_fetch_assoc($res)) {
        array_push($x,$r['domain']);
        array_push($y,$r['num']);
}

$title=_("Number of users per country");

draw_pie_graph(
    $graph,
    $x,
    $y,
    $title
);

// vim: sw=4 ts=4 expandtab
?>

<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'project_states.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();

theme('Pages Proofed Graphs','header');
echo "<center><h1><i>Pages Proofed Graphs</i></h1></center>";

echo "<br><br>";

echo "<center><img src=\"jpgraph_files/pages_daily.php?cori=increments&timeframe=curr_month\"></center><br>";
echo "<center><img src=\"jpgraph_files/pages_daily.php?cori=cumulative&timeframe=curr_month\"></center><br>";
echo "<center><img src=\"jpgraph_files/pages_daily.php?cori=increments&timeframe=all_time\"></center><br>";
echo "<center><img src=\"jpgraph_files/total_pages_by_month_graph.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/pages_daily.php?cori=cumulative&timeframe=all_time\"></center><br>";

theme('','footer');
?>

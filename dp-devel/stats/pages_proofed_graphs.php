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

echo "<center><img src=\"jpgraph_files/curr_month_pages_graph.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/cumulative_month_pages.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/total_pages_graph.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/total_pages_by_month_graph.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/cumulative_total_pages_graph.php\"></center><br>";

theme('','footer');
?>

<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'project_states.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();

theme('Created Projects Graphs','header');
echo "<center><h1><i>Created Projects Graphs</i></h1></center>";

echo "<br><br>";

echo "<center><img src=\"jpgraph_files/curr_month_proj_created_graph.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/cumulative_month_proj_created.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/total_proj_created_graph.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/cumulative_total_proj_created_graph.php\"></center><br>";

theme('','footer');
?>

<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'project_states.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();

theme('Proofed Projects Graphs','header');
echo "<center><h1><i>Proofed Projects Graphs</i></h1></center>";

echo "<br><br>";

echo "<center><img src=\"jpgraph_files/curr_month_proj_proofed_graph.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/cumulative_month_proj_proofed.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/total_proj_graph.php?which=proofed\"></center><br>";
echo "<center><img src=\"jpgraph_files/cumulative_total_proj_graph.php?which=proofed\"></center><br>";

theme('','footer');
?>

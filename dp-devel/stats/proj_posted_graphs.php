<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'project_states.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();

theme('Posted Projects Graphs','header');
echo "<center><h1><i>Posted Projects Graphs</i></h1></center>";

echo "<br><br>";

echo "<center><img src=\"jpgraph_files/curr_month_proj_posted_graph.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/cumulative_month_proj_posted.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/total_proj_graph.php?which=posted\"></center><br>";
echo "<center><img src=\"jpgraph_files/cumulative_total_proj_graph.php?which=posted\"></center><br>";

theme('','footer');
?>

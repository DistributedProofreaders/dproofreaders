<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'project_states.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();

theme('PPd Projects Graphs','header');
echo "<center><h1><i>PPd Projects Graphs</i></h1></center>";

echo "<br><br>";

echo "<center><img src=\"jpgraph_files/curr_month_proj_PPd_graph.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/cumulative_month_proj_PPd.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/total_proj_PPd_graph.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/cumulative_total_proj_PPd_graph.php\"></center><br>";

theme('','footer');
?>

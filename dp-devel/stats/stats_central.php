<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
if (!isset($_COOKIE['pguser'])) { include($relPath.'connect.inc'); } else { include($relPath.'dp_main.inc'); }
include($relPath.'theme.inc');
new dbConnect();

theme('Stats Central','header');
echo "<h1>Stats Central</h1>";

echo "<img src=\"jpgraph_files/curr_month_pages_graph.php\">";   
echo "<p>";
echo "<img src=\"jpgraph_files/total_pages_graph.php\">";

theme('','footer');
?>
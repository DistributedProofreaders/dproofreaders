<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
if (!isset($_COOKIE['pguser'])) { include($relPath.'connect.inc'); } else { include($relPath.'dp_main.inc'); }
include($relPath.'theme.inc');
new dbConnect();

theme('Stats Central','header');
echo "<center><h1><i>Stats Central</i></h1></center>";

echo "<table border='0' align='center' width='95%' cellspacing='2' cellpadding='2'>";
echo "<tr><form action='$code_url/stats/members/mbr_list.php' method='post'>";
echo "<td align='left'><font color='".$theme['color_headerbar_font']."'><input type='text' name='uname' size='20'>&nbsp;<input type='submit' value='Member Search'></td></form>";
echo "<form action='$code_url/stats/teams/tlist.php' method='post'>";
echo "<td align='right'><font color='".$theme['color_headerbar_font']."'><input type='text' name='tname' size='20'>&nbsp;<input type='submit' value='Team Search'></td></form>";
echo "</tr><tr><td align='center'><a href='$code_url/stats/members/mbr_list.php'>Member List</a></td><td align='center'><a href='$code_url/stats/teams/tlist.php'>Team List</a></td></tr></table><br>";

echo "<center><img src=\"jpgraph_files/curr_month_pages_graph.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/total_pages_graph.php\"></center>";

theme('','footer');
?>
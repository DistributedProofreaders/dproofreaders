<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'project_states.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();

$tally_name = @$_GET['tally_name'];
if (empty($tally_name))
{
    die("parameter 'tally_name' is undefined/empty");
}

$title = sprintf( _('Graphs for Pages Saved-as-Done in Round %s'), $tally_name );

theme($title,'header');
echo "<center><h1><i>$title</i></h1></center>";

echo "<br><br>";

echo "<center><img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=increments&timeframe=curr_month\"></center><br>";
echo "<center><img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=cumulative&timeframe=curr_month\"></center><br>";
echo "<center><img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=increments&timeframe=prev_month\"></center><br>";
echo "<center><img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=increments&timeframe=all_time\"></center><br>";
echo "<center><img src=\"jpgraph_files/pages_daily.php?tally_name=$tally_name&cori=cumulative&timeframe=all_time\"></center><br>";
echo "<center><img src=\"jpgraph_files/total_pages_by_month_graph.php?tally_name=$tally_name\"></center><br>";

theme('','footer');
?>

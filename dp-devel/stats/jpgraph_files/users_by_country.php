<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_pie.php');
include_once($relPath.'connect.inc');
new dbConnect();
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'iso_3166_list.inc');

$res=mysql_query("SELECT SUBSTRING_INDEX(email,'.',-1) AS domain,COUNT(*) AS num FROM users GROUP BY domain ORDER BY num DESC;");

$x=array(); $y=array();

while($r=mysql_fetch_assoc($res)) {
        array_push($x,$r['domain']);
        array_push($y,$r['num']);
}

$title="Number of users per country";

include("pie.inc");

?>

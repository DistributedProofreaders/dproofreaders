<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_pie.php');
include_once($relPath.'connect.inc');
new dbConnect();
include_once($relPath.'gettext_setup.inc');

$res=mysql_query("SELECT IFNULL(LEFT(u_intlang,2),'') AS intlang,COUNT(*) AS num FROM users GROUP BY intlang ORDER BY num DESC");

$x=array(); $y=array();

while($r=mysql_fetch_assoc($res)) {
	array_push($x,(
		$r['intlang']?
			dgettext("iso_639",eng_name($r['intlang'])):
			_("Browser Default")
		)." (%d)"
	);
	array_push($y,$r['num']);
}

$title="Number of users per user interface language";

include("pie.inc");

?>


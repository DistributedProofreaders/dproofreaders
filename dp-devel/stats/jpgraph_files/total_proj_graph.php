<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($code_dir.'/stats/statestats.inc');
include_once($relPath.'gettext_setup.inc');
include_once('common.inc');

new dbConnect();

//Create "projects Xed per day" graph for all known history

$psd = get_project_status_descriptor( $_GET['which'] );

$timeframe = _('since stats began');


//query db and put results into arrays
$result = mysql_query("
	SELECT date, SUM(num_projects) AS PC
	FROM project_state_stats
	WHERE $psd->state_selector
	GROUP BY date
	ORDER BY date
");

$mynumrows = mysql_numrows($result);


if ($mynumrows) {
	$base = mysql_result($result,0 , "PC");
	$datay1[0] = $base;
} else {
	$datay1[0] = 0;
}

$datax[0] = mysql_result($result, 0, "date");
$count = 1;


while ($count < $mynumrows) {
        $total = mysql_result($result, $count, "PC");
        $datay1[$count] = $total;
        $datay1[$count-1] = $total - $datay1[$count-1];
	if ($datay1[$count-1] < 0) $datay1[$count-1] = 0;
        $datax[$count] = mysql_result($result, $count, "date");
        $count++;
}
$datay1[$count - 1] = 0;
$datay1[$count] = 0;
$datay1[0] = 0;

draw_projects_graph(
	$datax,
	$datay1,
	'increments',
	$psd->color,
	"$psd->per_day_title ($timeframe)",
	300
);

?>

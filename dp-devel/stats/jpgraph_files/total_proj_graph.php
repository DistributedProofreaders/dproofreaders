<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($code_dir.'/stats/statestats.inc');
include_once($relPath.'gettext_setup.inc');
include_once('common.inc');

new dbConnect();

//Create "projects Xed per day" graph for all known history

switch ( $_GET['which'] )
{
	case 'created':
		$state_condition = "
			state NOT LIKE 'proj_new%'
		";
		$fill_color = 'green';
		$title = _("Projects Created Each Day Since Stats Began");
		break;

	case 'proofed':
		$state_condition = "
			state LIKE 'proj_submit%'
			OR state LIKE 'proj_correct%'
			OR state LIKE 'proj_post%'
		";
		$fill_color = 'blue';
		$title = _("Projects Proofed Each Day Since Stats Began");
		break;

	case 'PPd':
		$state_condition = "
			state LIKE 'proj_submit%'
			OR state LIKE 'proj_correct%'
			OR state LIKE 'proj_post_second%'
		";
		$fill_color = 'silver';
		$title = _("Projects Post-Processed Each Day Since Stats Began");
		break;

	case 'posted':
		$state_condition = "
			state LIKE 'proj_submit%'
			OR state LIKE 'proj_correct%'
		";
		$fill_color = 'gold';
		$title = _("Projects Posted Each Day Since Stats Began");
		break;

	default:
		die( "bad value for 'which'" );
}


//query db and put results into arrays
$result = mysql_query("
	SELECT date, SUM(num_projects) AS PC
	FROM project_state_stats
	WHERE $state_condition
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
	$fill_color,
	$title,
	300
);

?>

<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($code_dir.'/stats/statestats.inc');
include_once($relPath.'gettext_setup.inc');
include_once('common.inc');

new dbConnect();

// Create "cumulative projects Xed per day" graph for all days 
// since state stats started being recorded up to yesterday

switch ( $_GET['which'] )
{
	case 'created':
		$state_selector = "
			state NOT LIKE 'proj_new%'
		";
		$color = 'green';
		$title = _('Total Projects Created');
		break;

	case 'proofed':
		$state_selector = "
			state LIKE 'proj_submit%'
			OR state LIKE 'proj_correct%'
			OR state LIKE 'proj_post%'
		";
		$color = 'blue';
		$title = _('Total Projects Proofed');
		break;

	case 'PPd':
		$state_selector = "
			state LIKE 'proj_submit%'
			OR state LIKE 'proj_correct%'
			OR state LIKE 'proj_post_second%'
		";
		$color = 'silver';
		$title = _('Total Projects PPd');
		break;

	case 'posted':
		$state_selector = "
			state LIKE 'proj_submit%'
			OR state LIKE 'proj_correct%'
		";
		$color = 'gold';
		$title = _('Total Projects Posted to PG');
		break;

	default:
		die("bad value for 'which'");
}

$day = date("d");
$year  = date("Y");
$month = date("m");
$monthVar = date("F");
$today = $year."-".$month."-".$day;

//query db and put results into arrays
$result = mysql_query("SELECT sum(num_projects) as P, date FROM project_state_stats WHERE $state_selector GROUP BY DATE ORDER BY date ASC");
$i = 0;


while ($row = mysql_fetch_assoc($result)) {
	$datay1[$i] = $row['P'];
	$datax[$i] = $row['date'];
	$i++;
}

if (empty($datay1)) {
	$datay1[0] = 0;
}

draw_projects_graph(
	$datax,
	$datay1,
	'cumulative',
	$color,
	$title,
	360
);

?>

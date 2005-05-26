<?
$relPath="./../../pinc/";
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'page_tally.inc');
include_once('common.inc');
new dbConnect();

$tally_name = @$_GET['tally_name'];
if (empty($tally_name))
{
    die("parameter 'tally_name' is unset/empty");
}

///////////////////////////////////////////////////
//Total pages by month since beginning of stats

$result = mysql_query(
	select_from_site_past_tallies_and_goals(
		$tally_name,
		"SELECT {year_month}, SUM(tally_delta), SUM(goal)",
		"",
		"GROUP BY 1",
		"ORDER BY 1",
		""
	)
);

list($datax,$datay1,$datay2) = dpsql_fetch_columns($result);

draw_pages_graph(
	$datax,
	$datay1,
	$datay2,
	'monthly',
	'increments',
	'Pages Done Each Month Since the Beginning of Statistics Collection',
	1440
);

?>

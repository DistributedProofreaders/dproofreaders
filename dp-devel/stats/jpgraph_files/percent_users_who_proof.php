<?
$relPath="./../../pinc/";
include_once($relPath.'connect.inc');
include_once($relPath.'page_tally.php');
include_once('common.inc');
new dbConnect();


///////////////////////////////////////////////////
// For each month in which someone joined,
// get the number who joined,
// and the number of those who have proofed at least one page.
//
$result = mysql_query("
	SELECT
		FROM_UNIXTIME(date_created, '%Y-%m')
		  AS month,
		COUNT(*)
		  AS num_who_joined,
		SUM($user_P_page_tally_column > 0)
		  AS num_who_proofed
	FROM $users_table_with_tallies
	GROUP BY month
	ORDER BY month
");

// If there was a month when nobody joined,
// then the results will not include a row for that month.
// This may lead to a misleading graph,
// depending on its style.

while ( $row = mysql_fetch_object($result) )
{
        $datax[]  = $row->month;
        $data1y[] = 100 *  $row->num_who_proofed / $row->num_who_joined;
}

draw_simple_bar_graph(
	$datax,
	$data1y,
	1,
	'Percentage of New Users Who Went on to Proof By Month',
	'% of newly Joined Users who Proofed',
	640, 400,
	900
);

?>

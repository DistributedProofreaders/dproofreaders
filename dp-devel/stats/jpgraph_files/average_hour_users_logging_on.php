<?
$relPath="./../../pinc/";
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'connect.inc');
include_once('common.inc');
new dbConnect();

///////////////////////////////////////////////////
//Numbers of users logging on in each hour of the day, since the start of stats


//query db and put results into arrays


$result = mysql_query("
    SELECT hour, AVG(U_lasthour) AS users
    FROM user_active_log
    GROUP BY hour
    ORDER BY hour
");

list($datax,$datay) = dpsql_fetch_columns($result);

draw_simple_bar_graph(
	$datax,
	$datay,
	1,
	_('Average number of users newly logged in each hour'),
	_('Fresh Logons'),
	640, 400,
	58
);

?>

<?
$relPath="./../../pinc/";
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

// over an hour

$mynumrows = mysql_numrows($result);
        $count = 0;
        while ($count < $mynumrows) {
        $datay[$count] = mysql_result($result, $count,"users");
        $datax[$count] = mysql_result($result, $count,"hour");
            $count++;
        }

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

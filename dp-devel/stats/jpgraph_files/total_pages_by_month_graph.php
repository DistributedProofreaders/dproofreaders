<?
$relPath="./../../pinc/";
include_once($relPath.'connect.inc');
include_once('common.inc');
new dbConnect();


///////////////////////////////////////////////////
//Total pages by month since beginning of stats
//query db and put results into arrays
$result = mysql_query("
	SELECT CONCAT(year, '-', month) as T, SUM(pages) AS sumpages
	FROM pagestats
	GROUP BY year, month
	ORDER BY year ASC, month ASC
");

$mynumrows = mysql_numrows($result);
        $count = 0;
        while ($count < $mynumrows) {
        $datay[$count] = mysql_result($result, $count,"sumpages");
        $datax[$count] = mysql_result($result, $count,"T");
            $count++;
        }

draw_pages_graph(
	$datax,
	$datay,
	null,
	'monthly',
	'increments',
	'Pages Done Each Month Since the Beginning of Statistics Collection',
	1440
);

?>

<?
$relPath='../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'theme.inc');

theme("Miscellaneous Statistics", "header");

echo "<br><br><h2>Miscellaneous Statistics</h2><br>\n";

echo "<br>\n";

echo "<h3>Total Pages Proofed Since Statistics were Kept</h3>\n";

dpsql_dump_query("
	SELECT
		SUM(pages) as 'Total Pages Proofed So Far'
	FROM pagestats
");


echo "<h3>Top Ten Best Proofing Months</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		month as 'Month',
		year as 'Year',
		SUM(pages) as 'Pages Proofed',
		SUM(dailygoal) as 'Monthly Goal',
		IF(MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	GROUP BY month, year
	ORDER BY 3 DESC
	LIMIT 10
");

echo "<br>\n";

echo "<h3>Top Thirty Best Proofing Days Ever</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		date as 'Date',
		pages as 'Pages Proofed',
		IF(MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	ORDER BY 2 DESC
	LIMIT 30
");

echo "<br>\n";

echo "<h3>Top Ten Proofing Days This Year</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		date as 'Date',
		pages as 'Pages Proofed',
		IF(MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	WHERE year = YEAR(NOW())
	ORDER BY 2 DESC
	LIMIT 10
");


echo "<br>\n";

echo "<h3>Historical Log of Total Pages Proofed Per Month</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		year as 'Year',
		month as 'Month',
		sum(pages) as 'Pages Proofed',
		sum(dailygoal) as 'Monthly Goal'
	FROM pagestats
	WHERE  ( year < YEAR(NOW())  OR (year = YEAR(NOW()) AND month <= MONTH(NOW())))
	GROUP BY year, month
	ORDER BY 1, 2
");


echo "<br>\n";

echo "<h3>Total Pages Proofed Per Month</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		year as 'Year',
		month as 'Month',
		sum(pages) as 'Pages Proofed',
		sum(dailygoal) as 'Monthly Goal'
	FROM pagestats
	WHERE  ( year < YEAR(NOW())  OR (year = YEAR(NOW()) AND month <= MONTH(NOW())))
	GROUP BY year, month
	ORDER BY 'Pages Proofed' DESC");



echo "<br>\n";

echo "<h3>Months with most days over 5,000 pages</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		year as 'Year',
		month as 'Month',
		count(*) as 'Number of Days',
		IF(MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	WHERE pages >= 5000
	GROUP BY year, month
	HAVING count(*) > 7
	ORDER BY 3 DESC
	LIMIT 10
");

echo "<br>\n";

echo "<h3>Months with most days over 6,000 pages</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		year as 'Year',
		month as 'Month',
		count(*) as 'Number of Days',
		IF(MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	WHERE pages >= 6000
	GROUP BY year, month
	HAVING count(*) > 7
	ORDER BY 3 DESC
	LIMIT 10
");

echo "<br>\n";

echo "<h3>Months with most days over 7,000 pages</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		year as 'Year',
		month as 'Month',
		count(*) as 'Number of Days',
		IF(MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	WHERE pages >= 7000
	GROUP BY year, month
	HAVING count(*) > 7
	ORDER BY 3 DESC
	LIMIT 10
");

echo "<br>\n";

echo "<h3>Months with most days over 8,000 pages</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		year as 'Year',
		month as 'Month',
		count(*) as 'Number of Days',
		IF (MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	WHERE pages >= 7000
	GROUP BY year, month
	HAVING count(*) > 0
	ORDER BY 3 DESC
	LIMIT 10
");



theme("","footer");
?>


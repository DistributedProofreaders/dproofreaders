<?
$relPath='../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'theme.inc');

$title = _("Miscellaneous Statistics");
theme($title, "header");

echo "<br><br><h2>$title</h2><br>\n";

echo "<br>\n";

$sub_title = _("Total Pages Proofread Since Statistics Were Kept");
echo "<h3>$sub_title</h3>\n";

dpsql_dump_query("
	SELECT
		SUM(pages) as 'Total Pages Proofread So Far'
	FROM pagestats
");

$sub_title = _("Top Ten Best Proofreading Months");
echo "<h3>$sub_title</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		month as 'Month',
		year as 'Year',
		SUM(pages) as 'Pages Proofread',
		SUM(dailygoal) as 'Monthly Goal',
		IF(MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	GROUP BY month, year
	ORDER BY 3 DESC
	LIMIT 10
");

echo "<br>\n";

$sub_title = _("Top Thirty Best Proofreading Days Ever");
echo "<h3>$sub_title</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		date as 'Date',
		pages as 'Pages Proofread',
		IF(MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	ORDER BY 2 DESC
	LIMIT 30
");

echo "<br>\n";

$sub_title = _("Top Ten Proofreading Days This Year");
echo "<h3>$sub_title</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		date as 'Date',
		pages as 'Pages Proofread',
		IF(MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	WHERE year = YEAR(NOW())
	ORDER BY 2 DESC
	LIMIT 10
");


echo "<br>\n";

$sub_title = _("Historical Log of Total Pages Proofread Per Month");
echo "<h3>$sub_title</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		year as 'Year',
		month as 'Month',
		sum(pages) as 'Pages Proofread',
		sum(dailygoal) as 'Monthly Goal'
	FROM pagestats
	WHERE  ( year < YEAR(NOW())  OR (year = YEAR(NOW()) AND month <= MONTH(NOW())))
	GROUP BY year, month
	ORDER BY 1, 2
");


echo "<br>\n";

$sub_title = _("Total Pages Proofread Per Month");
echo "<h3></h3>\n";

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

$sub_title = _("Months with most days over 5,000 pages");
echo "<h3>$sub_title</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		year as 'Year',
		month as 'Month',
		count(*) as 'Number of Days',
		IF(MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	WHERE pages >= 5000
	GROUP BY year, month
	ORDER BY 3 DESC
	LIMIT 10
");

echo "<br>\n";

$sub_title = _("Months with most days over 6,000 pages");
echo "<h3>$sub_title</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		year as 'Year',
		month as 'Month',
		count(*) as 'Number of Days',
		IF(MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	WHERE pages >= 6000
	GROUP BY year, month
	ORDER BY 3 DESC
	LIMIT 10
");

echo "<br>\n";

$sub_title = _("Months with most days over 7,000 pages");
echo "<h3>$sub_title</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		year as 'Year',
		month as 'Month',
		count(*) as 'Number of Days',
		IF(MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	WHERE pages >= 7000
	GROUP BY year, month
	ORDER BY 3 DESC
	LIMIT 10
");

echo "<br>\n";

$sub_title = _("Months with most days over 8,000 pages");
echo "<h3>$sub_title</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		year as 'Year',
		month as 'Month',
		count(*) as 'Number of Days',
		IF (MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	WHERE pages >= 8000
	GROUP BY year, month
	ORDER BY 3 DESC
	LIMIT 10
");

echo "<br>\n";

$sub_title = _("Months with most days over 9,000 pages");
echo "<h3>$sub_title</h3>\n";

dpsql_dump_ranked_query("
	SELECT
		year as 'Year',
		month as 'Month',
		count(*) as 'Number of Days',
		IF (MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
	FROM pagestats
	WHERE pages >= 9000
	GROUP BY year, month
	ORDER BY 3 DESC
	LIMIT 10
");
echo "<br>\n";

theme("","footer");
?>


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

dpsql_dump_themed_query("
	SELECT
		SUM(pages) as 'Total Pages Proofread So Far'
	FROM pagestats
");

show_month_sums( 'top_ten' );

$sub_title = _("Top Thirty Best Proofreading Days Ever");
echo "<h3>$sub_title</h3>\n";

dpsql_dump_themed_ranked_query("
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

dpsql_dump_themed_ranked_query("
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

show_month_sums( 'all_chron' );
show_month_sums( 'all_by_pages' );

show_months_with_most_days_over(5000);
show_months_with_most_days_over(6000);
show_months_with_most_days_over(7000);
show_months_with_most_days_over(8000);
show_months_with_most_days_over(9000);

// -----------------------------------------------------------------------------

function show_month_sums( $which )
{
	switch ( $which )
	{
		case 'top_ten':
			$sub_title = _("Top Ten Best Proofreading Months");
			$order = '3 DESC';
			$limit = 'LIMIT 10';
			break;

		case 'all_chron':
			$sub_title = _("Historical Log of Total Pages Proofread Per Month");
			$order = '1,2'; // chronological
			$limit = '';
			break;

		case 'all_by_pages':
			$sub_title = _("Total Pages Proofread Per Month");
			$order = '3 DESC';
			$limit = '';
			break;

		default:
			die( "bad value for 'which': '$which'" );
	}

	echo "<h3>$sub_title</h3>\n";

	dpsql_dump_themed_ranked_query("
		SELECT
			year as 'Year',
			month as 'Month',
			SUM(pages) as 'Pages Proofread',
			SUM(dailygoal) as 'Monthly Goal',
			IF(MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
		FROM pagestats
		WHERE  ( year < YEAR(NOW())  OR (year = YEAR(NOW()) AND month <= MONTH(NOW())))
		GROUP BY year, month
		ORDER BY $order
		$limit
	");

	echo "<br>\n";
}

function show_months_with_most_days_over( $n )
{
	$sub_title = sprintf( _('Months with most days over %s pages'), number_format($n) );
	echo "<h3>$sub_title</h3>\n";

	dpsql_dump_themed_ranked_query("
		SELECT
			year as 'Year',
			month as 'Month',
			count(*) as 'Number of Days',
			IF(MONTH(NOW()) = month AND YEAR(NOW()) = year, '******',' ') as 'This Month?'
		FROM pagestats
		WHERE pages >= $n
		GROUP BY year, month
		ORDER BY 3 DESC
		LIMIT 10
	");

	echo "<br>\n";
}

theme("","footer");
?>


<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'page_tally.inc'); // get_page_tally_names()
include_once($relPath.'misc.inc'); // get_enumerated_param()

require_login();

$valid_tally_names = array_keys(get_page_tally_names());
$tally_name = get_enumerated_param($_GET, 'tally_name', null, $valid_tally_names);

// -----------------------------------

$now_timestamp = time();
$curr_year_month = strftime('%Y-%m', $now_timestamp);
$curr_year       = strftime('%Y',    $now_timestamp);

// -----------------------------------

$title = sprintf( _("Miscellaneous Statistics for Round %s"), $tally_name );
output_header($title);

echo "<h1>$title</h1>\n";

show_all_time_total();

show_month_sums( 'top_ten' );

show_top_days( 30, 'ever' );
show_top_days( 10, 'this_year' );

show_month_sums( 'all_chron' );
show_month_sums( 'all_by_pages' );

show_months_with_most_days_over(5000);
show_months_with_most_days_over(6000);
show_months_with_most_days_over(7000);
show_months_with_most_days_over(8000);
show_months_with_most_days_over(9000);

// -----------------------------------------------------------------------------

function show_all_time_total()
{
    global $tally_name;

    $sub_title = _("Total Pages Proofread Since Statistics Were Kept");
    echo "<h2>$sub_title</h2>\n";


    $site_tallyboard = new TallyBoard( $tally_name, 'S' );
    $holder_id = 1;
    echo number_format($site_tallyboard->get_current_tally($holder_id));

    echo "<br>\n";
    echo "<br>\n";
}

function show_top_days( $n, $when )
{
    global $tally_name, $curr_year, $curr_year_month;

    switch ( $when )
    {
        case 'ever':
            $where = '';
            $sub_title = sprintf( _('Top %d Proofreading Days Ever'), $n );
            break;

        case 'this_year':
            $where = "WHERE {year} = '$curr_year'";
            $sub_title = sprintf( _('Top %d Proofreading Days This Year'), $n );
            break;

        default:
            die( "bad value for 'when': '$when'" );
    }

    echo "<h2>$sub_title</h2>\n";

    dpsql_dump_themed_query(
        select_from_site_past_tallies_and_goals(
            $tally_name,
            "SELECT
                {date} as '" . DPDatabase::escape(_("Date")) . "',
                tally_delta as '" . DPDatabase::escape(_("Pages Proofread")) . "',
                IF({year_month} = '$curr_year_month', '******',' ') as '" 
                   . DPDatabase::escape(_("This Month?")) . "'",
            $where,
            "",
            "ORDER BY 2 DESC",
            "LIMIT $n"
        ), 1, DPSQL_SHOW_RANK
    );

    echo "<br>\n";
}

function show_month_sums( $which )
{
    global $tally_name, $curr_year_month;

    switch ( $which )
    {
        case 'top_ten':
            $sub_title = _("Top Ten Best Proofreading Months");
            $order = '2 DESC';
            $limit = 'LIMIT 10';
            break;

        case 'all_chron':
            $sub_title = _("Historical Log of Total Pages Proofread Per Month");
            $order = '1'; // chronological
            $limit = '';
            break;

        case 'all_by_pages':
            $sub_title = _("Total Pages Proofread Per Month");
            $order = '2 DESC';
            $limit = '';
            break;

        default:
            die( "bad value for 'which': '$which'" );
    }

    echo "<h2>$sub_title</h2>\n";

    dpsql_dump_themed_query(
        select_from_site_past_tallies_and_goals(
            $tally_name,
            "SELECT
                {year_month} as '" . DPDatabase::escape(_("Month")) . "',
                CAST(SUM(tally_delta) AS SIGNED) as '" 
                    . DPDatabase::escape(_("Pages Proofread")) . "',
                CAST(SUM(goal) AS SIGNED) as '" 
                    . DPDatabase::escape(_("Monthly Goal")) . "',
                IF({year_month} = '$curr_year_month', '******',' ') as '" 
                    . DPDatabase::escape(_("This Month?")) . "'",
            "",
            "GROUP BY 1",
            "ORDER BY $order",
            $limit
        ), 1, DPSQL_SHOW_RANK
    );

    echo "<br>\n";
}

function show_months_with_most_days_over( $n )
{
    global $tally_name, $curr_year_month;

    $sub_title = sprintf( _('Months with most days over %s pages'), number_format($n) );
    echo "<h2>$sub_title</h2>\n";

    dpsql_dump_themed_query(
        select_from_site_past_tallies_and_goals(
            $tally_name,
            "SELECT
                {year_month} as '" . DPDatabase::escape(_("Month")) . "',
                count(*) as '" . DPDatabase::escape(_("Number of Days")) . "',
                IF({year_month} = '$curr_year_month', '******',' ') as '" 
                    . DPDatabase::escape(_("This Month?")) . "'",
            "WHERE tally_delta >= $n",
            "GROUP BY 1",
            "ORDER BY 2 DESC",
            "LIMIT 10"
        ), 1, DPSQL_SHOW_RANK
    );

    echo "<br>\n";
}


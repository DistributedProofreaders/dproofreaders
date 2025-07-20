<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'page_tally.inc'); // get_page_tally_names()
include_once($relPath.'graph_data.inc');

require_login();

$valid_tally_names = array_keys(get_page_tally_names());
$tally_name = get_enumerated_param($_GET, 'tally_name', null, $valid_tally_names);
$start = get_param_matching_regex($_GET, 'start', null, '/^\d{4}-\d{1,2}$/', true);
$end = get_param_matching_regex($_GET, 'end', null, '/^\d{4}-\d{1,2}$/', true);

// -----------------------------------

$now_timestamp = time();
$curr_year_month = date('Y-m', $now_timestamp);
$curr_year = date('Y', $now_timestamp);

// -----------------------------------

$title = sprintf(_("Miscellaneous Statistics for Round %s"), $tally_name);

$js_data = "";
if (isset($start) && isset($end)) {
    $start_date = new DateTime("$start-01");
    $start_timestamp = (int)$start_date->format("U");
    $end_date = new DateTime("last day of $end-01");
    $end_timestamp = (int)$end_date->format("U");
    $data = get_site_tally_grouped($tally_name, 'year_month', $start_timestamp, $end_timestamp);
    $datax = array_column($data, 0);
    $datay = array_column($data, 1);

    $graph_config = [
        // %s is a tally name
        "title" => sprintf(_("%s Pages Completed by Month"), $tally_name),
        "downloadLabel" => _("Download Chart"),
        "yAxisLabel" => _("Pages"),
        "data" => [
            "" => [
                "x" => $datax,
                "y" => $datay,
                "type" => "line",
            ],
        ],
    ];
    $js_data .= build_svg_graph_inits([["barLineGraph", "graph", $graph_config]]);
}
output_header($title, NO_STATSBAR, [
    "js_files" => get_graph_js_files(),
    "js_data" => $js_data,
]);

echo "<h1>$title</h1>\n";

echo "<form action='$code_url/stats/misc_stats1.php'>
<label>" . _("Start") . " <input pattern='\d{4}-\d{1,2}' required value='" . attr_safe($start) . "' placeholder='YYYY-MM' name='start'></label>
<label>" . _("End") . " <input pattern='\d{4}-\d{1,2}' required value='" . attr_safe($end) . "' placeholder='YYYY-MM' name='end'></label>
<input type='hidden' name='tally_name' value='$tally_name'>
<input type='submit' value='" . _("Graph") . "'>
</form>
<br>
<div id='graph' style='max-width: 640px'></div>
";

show_all_time_total();

show_month_sums('top_ten');

show_top_days(30, 'ever');

show_month_sums('all_chron');
show_month_sums('all_by_pages');

// -----------------------------------------------------------------------------

function show_all_time_total()
{
    global $tally_name;

    $sub_title = _("Total Pages Proofread Since Statistics Were Kept");
    echo "<h2>$sub_title</h2>\n";


    $site_tallyboard = new TallyBoard($tally_name, 'S');
    $holder_id = 1;
    echo number_format($site_tallyboard->get_current_tally($holder_id));

    echo "<br>\n";
    echo "<br>\n";
}

function show_top_days($limit, $when)
{
    global $tally_name, $curr_year, $curr_year_month;

    switch ($when) {
        case 'ever':
            $where = '';
            $sub_title = sprintf(_('Top %d Proofreading Days Ever'), $limit);
            break;

        default:
            die("bad value for 'when': '$when'");
    }

    echo "<h2>$sub_title</h2>\n";

    echo "<table class='themed theme_striped stats'>";
    echo "<tr>";
    echo "<th>" . _("Rank") . "</th>";
    echo "<th>" . _("Date") . "</th>";
    echo "<th>" . _("Pages Proofread") . "</th>";
    echo "</tr>";
    $data = get_site_tally_grouped($tally_name, 'date', null, null, $limit, "tally_delta desc");
    $rank = 1;
    foreach ($data as $row) {
        echo "<tr>";
        echo "<td>" . $rank . "</td>";
        echo "<td>" . $row[0] . "</td>";
        echo "<td>" . $row[1] . "</td>";
        echo "</tr>";
        $rank += 1;
    }
    echo "</table>";
    echo "<br>\n";
}

function show_month_sums($which)
{
    global $tally_name, $curr_year_month;

    switch ($which) {
        case 'top_ten':
            $sub_title = _("Top Ten Best Proofreading Months");
            $sort = 'tally_delta desc';
            $limit = 10;
            break;

        case 'all_chron':
            $sub_title = _("Historical Log of Total Pages Proofread Per Month");
            $sort = 'dateunit'; // chronological
            $limit = null;
            break;

        case 'all_by_pages':
            $sub_title = _("Total Pages Proofread Per Month");
            $sort = 'tally_delta desc';
            $limit = null;
            break;

        default:
            die("bad value for 'which': '$which'");
    }

    echo "<h2>$sub_title</h2>\n";

    echo "<table class='themed theme_striped stats'>";
    echo "<tr>";
    echo "<th>" . _("Rank") . "</th>";
    echo "<th>" . _("Month") . "</th>";
    echo "<th>" . _("Pages Proofread") . "</th>";
    echo "<th>" . _("Monthly Goal") . "</th>";
    echo "</tr>";
    $data = get_site_tally_grouped($tally_name, 'year_month', null, null, $limit, $sort);
    $rank = 1;
    foreach ($data as $row) {
        echo "<tr>";
        echo "<td>" . $rank . "</td>";
        echo "<td>" . $row[0] . "</td>";
        echo "<td>" . $row[1] . "</td>";
        echo "<td>" . $row[2] . "</td>";
        echo "</tr>";
        $rank += 1;
    }
    echo "</table>";
    echo "<br>\n";
}

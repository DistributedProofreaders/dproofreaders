<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'page_tally.inc');
include_once('common.inc');

$day_options = ["0", "1", "7", "28", "180"];
$days = get_enumerated_param($_GET, "days", null, $day_options, true);

if ($days !== null) {
    display_graph($days);
    exit;
}

$title = _("Equilibria");

output_header($title);
echo "<h1>$title</h1>";
echo "<p>" . _('Only "today" is real-time; others updated at stats run time.') . "</p>";
foreach ($day_options as $days) {
    echo "<img src='?days=$days'><br><br>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function display_graph($d)
{
    $total_pages = 0;

    if ($d == 0) {
        $graph = init_pie_graph(660, 400, 5);

        $title = _("Net pages saved so far today");

        for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++) {
            $round = get_Round_for_round_number($rn);
            $site_stats = get_site_page_tally_summary($round->id);
            $data[] = $pages = $site_stats->curr_day_actual;
            $labels[] = "$round->id ($pages)";
            $total_pages += $pages;
        }
    } else {
        $graph = init_pie_graph(660, 400, 60);

        $title = sprintf(_("Net pages saved in preceding %s days"), $d);

        $now = time();
        for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++) {
            $round = get_Round_for_round_number($rn);
            $tallyboard = new TallyBoard($round->id, 'S');
            $data[] = $pages = $tallyboard->get_delta_sum(1, $now - (60 * 60 * 24 * $d), $now);
            $labels[] = "$round->id ($pages)";
            $total_pages += $pages;
        }
    }

    if ($total_pages == 0) {
        dpgraph_error(_("No pages saved in specified range"), 660, 400);
    }

    draw_pie_graph($graph, $labels, $data, $title);
}

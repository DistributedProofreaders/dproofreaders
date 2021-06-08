<?php
// Give information about a single round,
// including (most importantly) the list of projects available for work.

$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'showavailablebooks.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'gradual.inc');
include_once($relPath.'site_news.inc');
include_once($relPath.'mentorbanner.inc');
include_once($relPath.'quals.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'faq.inc');

require_login();

$round_id = get_enumerated_param($_GET, 'round_id', null, array_keys($Round_for_round_id_));
$round = get_Round_for_round_id($round_id);
$header_args = ["js_files" => ["$code_url/scripts/filter_project.js"]];

output_header("$round->id: $round->name", SHOW_STATSBAR, $header_args);

$uao = $round->user_access($pguser);

grant_user_access_if_sat($uao);

$round->page_top($uao);

$pagesproofed = get_pages_proofed_maybe_simulated();

alert_re_unread_messages($pagesproofed);

welcome_see_beginner_forum($pagesproofed, $round->id);

// show user how to access this round
if (!$uao->can_access) {
    show_user_access_object($uao, true /* will_autogrant */);
}

encourage_p1_to_p2($round->id);

show_news_for_page($round_id);

output_qual_projects($round_id);

if ($pagesproofed <= 100 && $ELR_round->id == $round_id) {
    if ($pagesproofed > 80) {
        echo "<p class='small italic'>";
        // TRANSLATORS: Simple Proofreading Rules are the strings listed in pinc/simple_proof_text.inc
        printf(_("After you proofread a few more pages, the following introductory Simple Proofreading Rules will be removed from this page. However, they are permanently available <a href='%s'>here</a> if you wish to refer to them later. (You can bookmark that link if you like.)"),
            "$code_url/faq/simple_proof_rules.php");
        echo "</p>";
    }

    include($relPath.'simple_proof_text.inc');
}

// What guideline document are we needing?
$round_doc_url = get_faq_url($round->document);
$rule = RandomRule::get_random($round->document, short_lang_code());

if ($rule && $pagesproofed >= 10) {
    echo "<h2>";
    echo _("Random Rule");
    echo "</h2>";

    if ($pagesproofed < 40) {
        echo "<p class='sans-serif small italic'>";
        printf(_("Now that you have proofread 10 pages you can see the Random Rule. Every time this page is refreshed, a random rule is selected from the <a href='%s'>Guidelines</a> and displayed in this section"),
            $round_doc_url);
        echo "<br>";
        echo _("(This explanatory line will eventually vanish.)");
        echo "</p>";
    }

    echo "<div id='random-rule'>";

    echo "<i>".$rule->subject."</i><br>";
    echo "<p>".$rule->rule."</p>";
    // TRANSLATORS: %1$s is the linked name of a random Guideline section.
    printf(_("See the %1\$s section of the <a href='%2\$s'>Guidelines</a>"),
        "<a href='$round_doc_url#".$rule->anchor."'>".$rule->subject."</a>",
        $round_doc_url);
    echo "</div>";
}

thoughts_re_mentor_feedback($pagesproofed);

if ($round->is_a_mentor_round()) {
    if (user_can_work_on_beginner_pages_in_round($round)) {
        mentor_banner($round);
    }
}

if ($pagesproofed > 20) {
    // Link to queues.
    echo "<h2>", _('Release Queues'), "</h2>";
    $res = DPDatabase::query("
        SELECT COUNT(*)
        FROM queue_defns
        WHERE round_id='{$round->id}'
    ");
    [$n_queues] = mysqli_fetch_row($res);
    if ($n_queues == 0) {
        echo sprintf(
            _("%s does not have any release queues. Any projects that enter the round's waiting area will automatically become available within a few minutes."),
            $round->id
        );
        echo "\n";
    } else {
        echo sprintf(
            _("Projects can be released into %2\$s via <a href='%1\$s'>various release queues</a>."),
            "$code_url/stats/release_queue.php?round_id={$round->id}",
            $round->id
        );
        echo "\n";
    }
}

// Don't display the filter block to newbies.
$show_filter_block = ($pagesproofed > 20);

show_projects_for_round($round, $show_filter_block);

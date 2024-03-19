<?php
// Give information about a single round,
// including (most importantly) the list of projects available for work.

$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'showavailablebooks.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'gradual.inc');
include_once($relPath.'site_news.inc');
include_once($relPath.'mentorbanner.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'faq.inc');

require_login();

$round = get_round_param($_GET, 'round_id');
$round_id = $round->id;
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

encourage_highest_round($pguser, $round->id);

show_news_for_page($round_id);


if ($pagesproofed < 100 && $ELR_round->id == $round_id) {
    echo "<div id='simple-proofreading-rules'>";

    echo "<h2>";
    echo _("Simple Proofreading Rules");
    echo "</h2>";

    if ($pagesproofed > 80) {
        echo "<p class='small italic'>";
        echo _("After you proofread a few more pages, these Simple Proofreading Rules will be removed from this page.");
        echo "</p>";
    }

    echo "<p>";
    echo _("1) Don't rewrap lines. Leave the ends of lines where they are in the image.");
    echo "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;";
    echo _("a) except, please put words that are broken across lines back together.");
    echo "<br><br>";
    echo _("2) Use a blank line before each paragraph and don't indent at the beginning of a paragraph.");
    echo "<br><br>";
    echo _("3) Remove extra spaces around punctuation mistakenly inserted by the OCR software.");
    echo "<br><br>";
    echo _("4) Don't correct the original spelling.");
    echo "<br><br>";
    // TRANSLATORS: PM = project manager
    echo _("5) When in doubt, make it look like the original and use [** <i>Notes for the next proofreader or PM would go here</i>] to flag the spot.");
    echo "</p>\n\n";


    echo "<p class='italic'>";
    echo sprintf(_("The <a href='%s'>Proofreading Guidelines</a> (for reference) provide much more detail."), get_faq_url("proofreading_guidelines.php"));
    echo "</p><p>\n";
    echo _("BEGINNERS ONLY projects are reserved for new proofreaders. After you have done 5-15 pages in total from these projects, please leave them for proofreaders who are newer than you. EASY projects make a good next step.");
    echo "</p></div>\n";
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
        printf(
            _("Now that you have proofread 10 pages you can see the Random Rule. Every time this page is refreshed, a random rule is selected from the <a href='%s'>Guidelines</a> and displayed in this section"),
            $round_doc_url
        );
        echo "<br>";
        echo _("(This explanatory line will eventually vanish.)");
        echo "</p>";
    }

    echo "<div id='random-rule'>";

    echo "<i>".$rule->subject."</i><br>";
    echo "<p>".$rule->rule."</p>";
    // TRANSLATORS: %1$s is the linked name of a random Guideline section.
    printf(
        _("See the %1\$s section of the <a href='%2\$s'>Guidelines</a>"),
        "<a href='$round_doc_url#".$rule->anchor."'>".$rule->subject."</a>",
        $round_doc_url
    );
    echo "</div>";
}

thoughts_re_mentor_feedback($pagesproofed);

if ($round->is_a_mentor_round()) {
    if (user_can_work_on_beginner_pages_in_round($round)) {
        mentor_banner($round);
    }
}

if ($pagesproofed >= 20) {
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
$show_filter_block = ($pagesproofed >= 20);

show_projects_for_round($round, $show_filter_block);

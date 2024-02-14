<?php
$relPath = './pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'ProjectTransition.inc'); // get_valid_transitions()
include_once($relPath.'project_states.inc');
include_once($relPath.'comment_inclusions.inc'); // parse_project_comments()
include_once($relPath.'page_table.inc'); // echo_page_table
include_once($relPath.'user_is.inc');
include_once($relPath.'Settings.inc');
include_once($relPath.'pg.inc');          // get_pg_catalog_link...
include_once($relPath.'theme.inc');
include_once($relPath.'../tools/proofers/PPage.inc'); // url_for_pi_*
include_once($relPath.'smoothread.inc'); // sr_echo_commitment_form // functions for smoothreading
include_once($relPath.'release_queue.inc'); // cook_project_selector
include_once($relPath.'user_project_info.inc');
include_once($relPath.'wordcheck_engine.inc'); // get_project_word_file
include_once($relPath.'links.inc'); // new_window_link
include_once($relPath.'project_edit.inc'); // check_user_can_load_projects
include_once($relPath.'forum_interface.inc'); // get_last_post_time_in_topic & get_url_*()
include_once($relPath.'faq.inc');
include_once($relPath.'daily_page_limit.inc'); // get_dpl_count_for_user_in_round
include_once($relPath.'special_colors.inc'); // load_special_days

// If the requestor is not logged in, we refer to them as a "guest".

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Usually, the user arrives here by clicking on the title of a project
// in a list of projects.
// But there are lots of other less-used pages that link here.

$MIN_DETAIL_LEVEL = 1;
$MAX_DETAIL_LEVEL = 4;
$DEFAULT_DETAIL_LEVEL = 3;

// Validate all the input
$projectid = get_projectID_param($_GET, 'id');
$expected_state = get_enumerated_param($_GET, 'expected_state', null, $PROJECT_STATES_IN_ORDER, true);
$detail_level = get_integer_param($_GET, 'detail_level', $DEFAULT_DETAIL_LEVEL, $MIN_DETAIL_LEVEL, $MAX_DETAIL_LEVEL);

$project = new Project($projectid);

// TRANSLATORS: this is the project page title.
// In a tabbed browser, the page-title passed to output_header() will appear
// in the tab, which tends to be small, as soon as you have a few of them.
// So, put the distinctive part of the page-title (i.e. the name of the
// project) first.
$title_for_theme = sprintf(_('"%s" project page'), $project->nameofwork);

$title = sprintf(_("Project Page for '%s'"), $project->nameofwork);

// -----------------------------------------------------------------------------

// Include structured metadata in LD+JSON format for this book
$ld_json_object = [
    "@context" => "http://schema.org",
    "@type" => "Book",
    "name" => $project->nameofwork,
    "author" => [
        "@type" => "Person",
        "name" => $project->authorsname,
    ],
    "inLanguage" => $project->languages,
    "genre" => $project->genre,
];

$extra_args = [
    "head_data" => '<script type="application/ld+json">' .
        json_encode($ld_json_object, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) .
    '</script>',
];

output_header($title_for_theme, NO_STATSBAR, $extra_args);

echo "<h1>" . html_safe($title) . "</h1>\n";

maybe_output_new_proofer_project_message($project);

if (!$user_is_logged_in) {
    // Guests see a reduced version of the project page.

    [$top_blurb, $bottom_blurb] = decide_blurbs();
    do_blurb_box($top_blurb);
    do_project_info_table();
    do_blurb_box($bottom_blurb);

    do_smooth_reading();

    echo "<br>\n";
    return;
} else { // $user_is_logged_in
    upi_set_t_latest_home_visit(
        $pguser,
        $project->projectid,
        time(),
        $project->PPer_is_current_user or $project->PPVer_is_current_user
    );
}

if ($detail_level == 1) {
    do_expected_state();
    do_detail_level_switch();

    do_project_info_table();
} else {
    // Detail level 2 should show the information
    // that is usually wanted by the people who usually work with
    // the project in its current state.

    do_detail_level_switch();
    do_expected_state();

    [$top_blurb, $bottom_blurb] = decide_blurbs();

    do_blurb_box($top_blurb);
    do_project_info_table();
    do_edit_above();
    do_blurb_box($bottom_blurb);

    do_early_uploads();
    do_waiting_queues();
    do_event_subscriptions();
    do_post_files();
    do_postcomments();
    do_smooth_reading();
    do_ppv_report();
    do_change_state();

    if ($detail_level >= 3) {
        // Stuff that's (usually) only of interest to
        // PMs/PFs/SAs and curious others.
        do_holds();
        do_history();
        do_images();
        do_extra_files();
        do_page_summary();
        if ($detail_level >= 4) {
            do_page_table();
        }
    }

    do_detail_level_switch();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_detail_level_switch()
{
    global $project, $detail_level, $MIN_DETAIL_LEVEL, $MAX_DETAIL_LEVEL;

    echo "<p>";
    echo sprintf(
        _('This page is being presented at detail level %d.'),
        $detail_level
    );
    echo "\n";
    echo _('Switch to:'), "\n";
    for ($v = $MIN_DETAIL_LEVEL; $v <= $MAX_DETAIL_LEVEL; $v++) {
        if ($v != $detail_level) {
            $url = "project.php?id={$project->projectid}&amp;expected_state={$project->state}&amp;detail_level=$v";
            echo "<a href='$url'>$v</a>\n";
        }
    }
    echo "</p>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_expected_state()
{
    global $project, $expected_state;

    if (!empty($expected_state) && $expected_state != $project->state) {
        echo "<p class='warning'>";
        echo sprintf(
            _('Warning: Project "%1$s" is no longer in state "%2$s"; it is now in state "%3$s".'),
            html_safe($project->nameofwork),
            project_states_text($expected_state),
            project_states_text($project->state)
        );
        echo "</p>";
        echo "<br>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function decide_blurbs()
{
    global $project, $pguser, $code_url;

    $projectid = $project->projectid;
    $state = $project->state;
    $round = get_Round_for_project_state($state);

    try {
        $project->validate_can_be_proofed_by_current_user();
    } catch (ProjectNotInRoundException $exception) {
        // Rather than blurbs that say it's not in a round,
        // just don't have any blurbs.
        return [null, null];
    } catch (UserNotQualifiedForRoundException $exception) {
        $msg = $exception->getMessage() . "<br>" . sprintf(
            // TRANSLATORS: %2$s is the round ID (eg: P1)
            _('Please visit <a href="%1$s">the %2$s home</a> to find out what happens in this round and how you can qualify to work in it.'),
            "$code_url/tools/proofers/round.php?round_id=$round->id",
            $round->id,
        );
        return [$msg, $msg];
    } catch (ProjectException | UserAccessException $exception) {
        $msg = $exception->getMessage();
        return [$msg, $msg];
    }

    $num_pages_available = $project->get_num_pages_in_state($round->page_avail_state);

    if ($num_pages_available == 0) {
        $blurb =
            _("Round Complete")
            . "<br>"
            . _("There are no pages available for proofreading.");
        return [$blurb, $blurb];
    }

    try {
        validate_user_can_get_pages_in_project(new User($pguser), $project, $round);
    } catch (UserAccessException $exception) {
        $blurb = $exception->getMessage();
        return [$blurb, $blurb];
    }

    // Check whether the user is blocked by a daily page limit.
    // Arguably, you'd expect this to be determined in validate_user_can_get_pages_in_project(),
    // but we also want to produce a warning (when the project is covered by a DPL)
    // even when the user *isn't* blocked, which you can't really expect of that function.
    //
    $page_limit_warning = "";
    if ($round->has_a_daily_page_limit()) {
        $user_dpl_count = get_dpl_count_for_user_in_round($pguser, $round);
        if ($user_dpl_count >= $round->daily_page_limit) {
            // User has reached this round's DPL.
            $msg = sprintf(
                _('%1$s limits you to %2$d page-saves per day, and you have already reached that limit today.'),
                $round->id,
                $round->daily_page_limit
            );
            $blocked_by_limit_message = "<span class='warning'>$msg</span>";
            return [$blocked_by_limit_message, $blocked_by_limit_message];
        } elseif ($user_dpl_count >= 0.9 * $round->daily_page_limit) {
            // User hasn't reached this round's DPL,
            // but they should be warned that there *is* a DPL for this round.
            $msg = sprintf(
                _('You may save up to %1$d pages in %2$s before server midnight. Your current count is <b>%3$d</b>.'),
                $round->daily_page_limit,
                $round->id,
                $user_dpl_count
            );
            $page_limit_warning = "<br>\n<span class='warning'>$msg</span>\n";
        } else {
            // They're not that close to reaching the DPL,
            // so don't bother warning them about it.
        }
    }

    {
        // If there's any proofreading to be done, this is the link to use.
        $url = url_for_pi_do_whichever_page($projectid, $state, true);

        // If the "Start Proofreading" text is ever changed, be sure and grep
        // through the codebase for any other instances and change them too.
        $label = _("Start Proofreading");
        $proofreading_link = "<b><a href='$url'>$label</a></b>";

        // When were the project comments last modified?
        $comments_timestamp = $project->t_last_change_comments;
        $comments_time_str = icu_date_template("long+time", $comments_timestamp);
        $comments_last_modified_blurb = _("Project Comments last modified:") . " " . $comments_time_str;

        // Other possible components of blurbs:
        $please_scroll_down = _("Please scroll down and read the Project Comments for any special instructions <b>before</b> proofreading!");
        $the_link_appears_below = sprintf(_("The '%s' link appears below the Project Comments"), $label);
        $comments_have_changed =
            "<p class='error'>"
            . _("Project Comments have changed!")
            . "</p>";

        // ---

        $bottom_blurb =
            $comments_last_modified_blurb
            . $page_limit_warning
            . "<br>"
            . $proofreading_link;

        // Has the user saved a page of this project since the project comments were
        // last changed? If not, it's unlikely they've seen the revised comments.
        $sql = "
            SELECT {$round->time_column_name}
            FROM $projectid
            WHERE state='{$round->page_save_state}' AND {$round->user_column_name}='" . DPDatabase::escape($pguser) . "'
            ORDER BY {$round->time_column_name} DESC
            LIMIT 1
        ";
        $res = DPDatabase::query($sql);
        $row = mysqli_fetch_assoc($res);
        if (!$row) {
            // The user has not saved any pages for this project.
            $top_blurb =
                $please_scroll_down
                . "<br>"
                . $comments_last_modified_blurb
                . "<br>"
                . $the_link_appears_below;
        } else {
            // The user has saved a page for this project.
            $my_latest_save_timestamp = $row[$round->time_column_name];

            if ($my_latest_save_timestamp < $comments_timestamp) {
                // The latest page-save was before the comments were revised.
                // The user probably hasn't seen the revised project comments.
                $top_blurb =
                    $comments_have_changed
                    . $please_scroll_down
                    . "<br>"
                    . $comments_last_modified_blurb
                    . "<br>"
                    . $the_link_appears_below;
            } else {
                // The latest page-save was after the comments were revised.
                // We'll assume that the user has read the new comments.
                $top_blurb =
                    $please_scroll_down
                    . "<br>"
                    . $comments_last_modified_blurb
                    . $page_limit_warning
                    . "<br>"
                    . $proofreading_link;
            }
        }
    }

    return [$top_blurb, $bottom_blurb];
}

// -----------------------------------------------

function do_blurb_box($blurb)
{
    if (is_null($blurb)) {
        return;
    }

    echo "<table class='blurb-box'>";
    echo "<tr><td class='center-align'>";
    echo $blurb;
    echo "</td></tr>";
    echo "</table>";
    echo "<br>";
    echo "\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_project_info_table()
{
    global $project, $code_url;
    global $user_is_logged_in;

    $projectid = $project->projectid;
    $state = $project->state;

    $round = get_Round_for_project_state($state);
    // Note that $round may be NULL;

    echo "<table class='basic' style='max-width: 630px' id='project_info_table'>";

    // -------------------------------------------------------------------------
    // The state of the project

    $available_for_SR = $project->is_available_for_smoothreading();

    $right = project_states_text($project->state);
    if ($round) {
        $extra = $round->description;
        $right = "$right ($extra)";
    } elseif ($available_for_SR) {
        $sr_deadline_str = icu_date_template("long+time", $project->smoothread_deadline);
        $sr_sentence = sprintf(
            _('This project has been made available for Smooth Reading until %s server time.'),
            "<b>$sr_deadline_str</b>"
        );
        $right = "$right<br>$sr_sentence";
    }
    echo_row_a(_("Project State"), $right);

    if ($project->state == PROJ_DELETE) {
        // Change anything that looks like a projectid into a link.
        $cooked_reason = preg_replace(
            '/\b(projectID[0-9a-f]{13})\b/',
            '<a href="project.php?id=\1">\1</a>',
            $project->deletion_reason
        );
        echo_row_a(_("Reason for Deletion"), $cooked_reason);
    }

    // -------------------------------------------------------------------------
    // Information about the work itself (independent of DP)

    echo_row_a(_("Title"), $project->nameofwork, true);
    echo_row_a(_("Author"), $project->authorsname, true);
    echo_row_a(_("Language"), $project->language, true);
    echo_row_a(_("Genre"), _($project->genre), true);
    echo_row_a(_("Difficulty"), get_project_difficulties()[$project->difficulty], true);

    // -------------------------------------------------------------------------
    // Basic DP info

    if (!empty($project->special_code)) {
        $spec_code = $project->special_code;
        if (startswith($spec_code, 'Birthday') ||
            startswith($spec_code, 'Otherday')
        ) {
            $spec_display = $spec_code;
        } else {
            $special_days = load_special_days(true);
            $spec_display = $special_days[$spec_code]["display_name"] ?? "($spec_code)";
        }

        echo_row_a(_("Special Day"), $spec_display, true);
    }

    // -------

    echo_row_a(_("Project ID"), $project->projectid);

    if ($project->clearance_line_can_be_seen_by_current_user()) {
        echo_row_a(_("Clearance Line"), $project->clearance, true);
    }

    // -------------------------------------------------------------------------
    // People who have certain roles with respect to the project

    if ($project->image_source_name) {
        echo_row_a(_("Image Source"), $project->image_source_name, true);
    }

    // We choose not to show guests anything involving users' names.
    if ($user_is_logged_in) {
        echo_row_a(_("Project Manager"), $project->username, true);

        if ($project->PPer) {
            echo_row_a(_("Post Processor"), $project->PPer, true);
        }

        if ($project->PPVer) {
            echo_row_a(_("PP Verifier"), $project->PPVer, true);
        }

        echo_row_a(_("Credits line so far"), $project->credits_line, true);
    }

    // -------------------------------------------------------------------------
    // Current activity

    $formatted_now = date("H:i");
    $ct = _("Current Time");
    $current_time_addition = "&nbsp;&nbsp;&nbsp;" . html_safe("($ct: $formatted_now)");

    echo_row_a(
        _("Last Edit of Project Info"),
        icu_date_template("long+time", $project->t_last_edit)
        . $current_time_addition
    );

    echo_row_a(
        _("Last State Change"),
        icu_date_template("long+time", $project->modifieddate),
        true
    );

    if ($round) {
        $last_proofread_time = $project->get_last_proofread_time($round);
        if ($last_proofread_time) {
            $lastproofed = icu_date_template("long+time", $last_proofread_time);
        } else {
            $lastproofed = _("Project has not been proofread in this round.");
        }
        echo_row_a(_("Last Proofread"), $lastproofed, true);
    }

    // -------------------------------------------------------------------------

    // We choose not to show guests the word lists.
    if ($user_is_logged_in) {
        $good_bad = [
            'good' => _("Good Words"),
            'bad' => _("Bad Words"),
        ];

        $links = '';
        foreach ($good_bad as $gb => $label) {
            $f = get_project_word_file($projectid, $gb);
            if ($f->size > 0) {
                $links .= new_window_link($f->abs_url, $label);
                $links .= " - " . _("Last modified") . ": " . icu_date_template("long+time", $f->mod_time);
            } else {
                $links .= $label . " " . _("(empty)");
            }
            $links .= "<br>";
        }

        echo_row_a(_("Word Lists"), $links);
    }

    if ($project->pages_table_exists && !$project->is_utf8) {
        echo_row_a(_("Encoding"), "<span class='error'>" . _("Project table is not UTF-8.") . "</span>");
    }

    $project_charsuites = [];
    foreach ($project->get_charsuites() as $charsuite) {
        $project_charsuites[] = "<a href='tools/charsuites.php?projectid=$projectid#" . attr_safe($charsuite->name) . "'>" . html_safe($charsuite->title) . "</a>";
    }
    echo_row_a(_("Character Suites"), implode(", ", $project_charsuites));

    // -------------------------------------------------------------------------

    $state = $project->state;
    if ($state == PROJ_SUBMIT_PG_POSTED) {
        $postednum = $project->postednum;
        echo_row_a(
            _("PG etext number"),
            $postednum . " &ndash; " .
            sprintf(
                _("<a href='%s'>Read this text</a> at Project Gutenberg"),
                get_pg_catalog_url_for_etext($postednum)
            )
        );
    }

    // -------------------------------------------------------------------------
    // Forum topic

    $topic_id = $project->topic_id;
    if (!empty($topic_id)) {
        $last_post_date = get_last_post_time_in_topic($topic_id);
        $last_post_date = icu_date_template("long+time", $last_post_date);
        echo_row_a(_("Last Forum Post"), $last_post_date, true);
    }

    // If the topic is only visible to logged-in users,
    // there's little point showing guests the link to it.
    if ($user_is_logged_in) {
        if (($state == PROJ_DELETE) && ($topic_id == "")) {
            echo_row_a(_("Forum"), _("The project has been deleted, and no discussion exists."), true);
        } else {
            if ($topic_id == "") {
                $blurb = html_safe(_("Start a discussion about this project"));
                $url = "$code_url/tools/proofers/project_topic.php?project=$projectid";
                echo_row_a(_("Forum"), "<a href='$url' onclick='this.outerHTML=\"$blurb\";'>$blurb</a>");
            } else {
                $details = get_topic_details($topic_id);
                if ($details) {
                    $replies = sprintf(_("(%d replies)"), $details['num_replies']);
                } else {
                    $replies = '';
                }
                $blurb = html_safe(_("Discuss this project"));
                $url = get_url_to_view_topic($topic_id);
                echo_row_a(_("Forum"), "<a href='$url'>$blurb</a> $replies");
            }
        }
    }

    // -------------------------------------------------------------------------

    // For now, we say that guests can't see page details.
    if ($user_is_logged_in) {
        global $detail_level;
        if ($detail_level >= 4) {
            // We'll call do_page_table later, so we don't need the "Page Detail" link.
        } else {
            $detail = "";
            if ($project->check_pages_table_exists($detail)) {
                $url = "$code_url/tools/project_manager/page_detail.php?project=$projectid&amp;show_image_size=0";
                $blurb = html_safe(_("Images, Pages Proofread, & Differences"));
                $url2 = "$url&amp;select_by_user";
                $blurb2 = html_safe(_("Just my pages"));
                $detail = "<a href='$url'>$blurb</a> &middot; <a href='$url2'><b>$blurb2</b></a>";
                if ($project->has_entered_formatting_round()) {
                    $url3 = "$code_url/tools/project_manager/page_compare.php?project=$projectid";
                    $blurb3 = html_safe(_("Compare without formatting"));
                    $detail .= " &middot; <a href='$url3'>$blurb3</a>";
                }
            }
            echo_row_a(_("Page Detail"), $detail);
        }
    }

    // -------------------------------------------------------------------------
    // Personal data with respect to this project

    // If you're not logged in, we certainly can't show your personal data.
    if ($user_is_logged_in) {
        global $detail_level;
        if ($round && $detail_level > 1) {
            recentlyproofed(0);
            recentlyproofed(1);
        }
    }

    // -------------------------------------------------------------------------
    // Comments

    global $pguser;

    $postcomments = get_formatted_postcomments($project->projectid);

    if ($postcomments != '') {
        if ($available_for_SR) {
            $class = 'sr-instructions';
            echo_row_b(_("Instructions for Smooth Reading"), '', $class);
            echo_row_c($postcomments);
        }
        // Postcomments should always be shown to those directly involved with the project (the
        // first three conditions). However, when the project is available for PPVing, the prospective
        // PPVer should be able to read the PPer's comments without checking out the project.
        // Likewise, when it's available for PPing, a prospective PPer should be able
        // to read them
        elseif ($project->PPer_is_current_user ||
                 $project->PPVer_is_current_user ||
                 $project->can_be_managed_by_current_user ||
                 $state == PROJ_POST_FIRST_AVAILABLE && user_can_work_in_stage($pguser, 'PP') ||
                 $state == PROJ_POST_SECOND_AVAILABLE && user_can_work_in_stage($pguser, 'PPV')) {
            $class = 'post-processor-comments';
            echo_row_b(_("Post Processor's Comments"), '', $class);
            echo_row_c($postcomments);
        }
    }

    // --------

    // For now, we suppress Project Comments for guests.
    // (They might be confused by the instructions for proofreaders.)
    if ($user_is_logged_in) {
        $comments = $project->comments;

        // insert e.g. templates and biographies
        $comments = parse_project_comments($project);

        if ($comments == '') {
            // Put in *something*, otherwise it'll probably look odd.
            $comments = '&nbsp;';
        }

        if ($round) {
            $a = sprintf(
                _("The <a href='%s'>Guidelines</a> give detailed instructions for working in this round."),
                get_faq_url($round->document)
            );
            $b = _('The instructions below are particular to this project, and <b>take precedence over those guidelines</b>.');

            $time_str = icu_date_template("long+time", $project->t_last_change_comments);
            $c = "(" . _("Last modified") . ": " . $time_str . ")";

            $comments_blurb = "$a<br>$b<br>$c";
        } else {
            $comments_blurb = "";
        }

        $class = 'project-comments';
        echo_row_b("<span id='project-comments'>" . _("Project Comments") . "</span>", $comments_blurb, $class);

        echo_row_c($comments);
    }

    // -------------------------------------------------------------------------

    echo "</table>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function echo_row_a($left, $right, $escape_right = false)
{
    if ($escape_right) {
        $right = html_safe($right);
    }
    echo "<tr>";
    echo "<th class='label'>" . html_safe($left) . "</th>";
    echo "<td colspan='4'>$right</td>";
    echo "</tr>\n";
}

function echo_row_b($top, $bottom, $class = '')
{
    echo "<tr>";
    echo "<td colspan='5' class='$class center-align'>";
    echo "<span class='bold large'>$top</span>";
    if ($bottom) {
        echo "<br>$bottom";
    }
    echo "</td>";
    echo "</tr>\n";
}

function echo_row_c($content)
{
    echo "<tr>";
    echo "<td colspan='5'>";
    echo $content;
    echo "</td>";
    echo "</tr>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function recentlyproofed($wlist)
{
    global $project, $pguser;

    $projectid = $project->projectid;
    $state = $project->state;

    $round = get_Round_for_project_state($state);
    assert($round);

    if ($wlist == 0) {
        $top = _("DONE");
        $bottom = "(<b>"._("My Recently Completed")."</b> - "._("pages I've finished proofreading, that are still available for correction)");
        $state_condition = "state='{$round->page_save_state}'";
        $class = 'done_current';
    } else {
        $top = _("IN PROGRESS");
        $bottom = "(<b>"._("My Recently Proofread")."</b> - "._("pages I haven't yet completed)");
        $state_condition = "(state='{$round->page_temp_state}' OR state='{$round->page_out_state}')";
        $class = 'in_progress';
    }

    echo_row_b($top, $bottom, $class);

    $recentNum = 5; // if this is > 5 more rows will be shown

    if (is_formatting_round($round)) {
        $wordcheck_query = "NULL as wordcheck_status";
    } else {
        $wordcheck_query = "
            (
                SELECT count(*)
                FROM wordcheck_events
                WHERE projectid = '$projectid' AND
                    image = $projectid.image AND
                    username = $round->user_column_name AND
                    round_id = '$round->id'
            ) as wordcheck_status
        ";
    }

    $sql = "
        SELECT $projectid.image, $projectid.state, {$round->time_column_name}, $wordcheck_query
        FROM $projectid
        WHERE {$round->user_column_name}='" . DPDatabase::escape($pguser) . "'
            AND $state_condition
        ORDER BY {$round->time_column_name} DESC
        LIMIT $recentNum
    ";
    $result = DPDatabase::query($sql);
    $rownum = 0;
    $numrows = mysqli_num_rows($result);

    while ($rownum < $numrows) {
        echo "<tr>";
        $colnum = 0;
        while (($colnum < 5) && ($rownum < $numrows)) {
            $row = mysqli_fetch_assoc($result);
            $imagefile = $row["image"];
            $timestamp = $row[$round->time_column_name];
            $pagestate = $row["state"];
            $eURL = url_for_pi_do_particular_page(
                $projectid,
                $state,
                $imagefile,
                $pagestate,
                true
            );

            if ($row["wordcheck_status"] == null) {
                $wordcheck_status = '';
            } elseif ($row["wordcheck_status"] > 0) {
                $wordcheck_status = '&nbsp;<span title="' . _('This page was WordChecked.') . '">&check;</span>';
            } else {
                $wordcheck_status = '&nbsp;<span title="' . _('This page was not WordChecked.') . '">&#x2717;</span>';
            }
            echo "<td class='center-align'>";
            echo "<a href=\"$eURL\">";
            // TRANSLATORS: This is an ICU-formatted string
            echo icu_date(_("MMM dd"), $timestamp) . ": " . $imagefile;
            echo "</a>$wordcheck_status";
            echo "</td>";
            $colnum++;
            $rownum++;
        }
        // fill up last row with blanks
        while ($colnum++ < 5) {
            echo "<td>&nbsp;</td>";
            $rownum++;
        }
        echo "</tr>";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_edit_above()
{
    global $project, $code_url;

    $links = [];
    if ($project->can_be_managed_by_current_user) {
        $links[] = "<a href='$code_url/tools/project_manager/editproject.php?action=edit&amp;project=$project->projectid&amp;return=" .
            // TRANSLATORS: "Edit" as in modify as opposed to correct
            urlencode($_SERVER["REQUEST_URI"]) . "'>" . _("Edit the above information") . "</a>";
        if ($project->dir_exists) {
            $links[] = "<a href='$code_url/tools/project_manager/edit_project_word_lists.php?projectid=$project->projectid&amp;return=" .
                // TRANSLATORS: "Edit" as in modify as opposed to correct
                urlencode($_SERVER["REQUEST_URI"]) . "'>" . _("Edit project word lists") ."</a>";
        }
    }

    if ($project->user_can_do_quick_check()) {
        $links[] = "<a href='$code_url/tools/project_manager/project_quick_check.php?projectid=$project->projectid'>" .
            _("Project Quick Check") . "</a>";
    }

    if ($links) {
        echo "<p>" . implode(" | ", $links) . "</p>";
    }

    if (!$project->can_be_managed_by_current_user) {
        return;
    }

    if (! user_has_project_loads_disabled()) {
        echo "<p>";
        echo "<a href='$code_url/tools/project_manager/editproject.php?action=clone&amp;project=$project->projectid'>";
        echo _("Clone this project");
        echo "</a>";
        echo "</p>";
    }
    // possibly print a message, which will appear where the clone project
    // link would otherwise be, and near where the add/replace files
    // section would be.
    check_user_can_load_projects(false); // keep going, even if they can't
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_early_uploads()
{
    global $project, $code_url, $pguser;
    global $uploads_host, $uploads_account, $uploads_password;
    if (!$project->can_be_managed_by_current_user) {
        return;
    }

    $projectid = $project->projectid;

    $add_reminder = false;

    $user_dir = str_replace(' ', '_', $pguser);

    // Load text+images from uploads area into project.
    // Can do this if it's a new project (as measured by the state it's in)
    // If the user is disabled from uploading new projects, they can only
    // do this if the project already has some pages loaded, but there is
    // no need to display a message reminding them that they can't, as
    // there will already be one instead of the clone project link, just above.
    if (user_can_add_project_pages($projectid)) {
        echo "<br>\n";
        echo "<form method='get' action='$code_url/tools/project_manager/add_files.php'>\n";
        echo "<input type='hidden' name='project' value='$projectid'>\n";
        echo _("Add/Replace text and images from directory or zip file:");
        echo "<br>\n";
        $initial_rel_source = "Users/$user_dir/";
        echo "~$uploads_account/ <input type='text' name='rel_source' size='50' value='$initial_rel_source' required>";
        echo "<br>\n";
        echo "<p>\n";
        echo _("Remember to upload the illustration files as well as the page files!");
        echo "</p>\n";
        echo "<input type='submit' value='", attr_safe(_("Add/Replace")), "'>";
        echo "<br>\n";
        echo "</form>\n";
        $add_reminder = true;
    }

    if ($add_reminder) {
        echo "<p>";
        echo sprintf(_("To upload your files to %s, use the <a href='tools/project_manager/remote_file_manager.php'>remote file manager</a>."), "~$uploads_account");

        // if the site has $uploads_host set, show the FTP details
        if ($uploads_host) {
            echo "<br>";
            echo sprintf(
                _('For FTP uploads, use host=%1$s account=%2$s password=%3$s'),
                "<b>$uploads_host</b>",
                "<b>$uploads_account</b>",
                "<i>" . html_safe($uploads_password) . "</i>"
            );
        }

        echo "</p>";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_waiting_queues()
{
    global $project, $code_url;

    $round = get_Round_for_project_state($project->state);

    if (is_null($round)) {
        return;
    }
    if ($project->state != $round->project_waiting_state) {
        return;
    }

    // Okay, so the project is in some round's waiting state.
    // What queues is it in, if any?

    echo "<h2>";
    echo _("Queues");
    echo "</h2>\n";

    $sql = "
        SELECT name, project_selector
        FROM queue_defns
        WHERE round_id='{$round->id}'
        ORDER BY ordering
    ";
    $res = DPDatabase::query($sql);
    if (mysqli_num_rows($res) == 0) {
        // No queues defined for this round.
        echo sprintf(
            _('There are no queues defined for round %s, so this project should be automatically released within a few minutes.'),
            $round->id
        );
    } else {
        echo sprintf(
            _('This project is in the following round %s queues:'),
            $round->id
        );
        echo "<br>\n";

        echo "<ul>\n";
        $n_queues = 0;
        while ([$q_name, $q_project_selector] = mysqli_fetch_row($res)) {
            $cooked_project_selector = cook_project_selector($q_project_selector);
            $sql = "
                SELECT projectid
                FROM projects
                WHERE projectid='{$project->projectid}' AND ($cooked_project_selector)
            ";
            $res2 = DPDatabase::query($sql);
            if (mysqli_num_rows($res2) > 0) {
                $n_queues += 1;
                $enc_q_name = urlencode($q_name);
                $url = "$code_url/stats/release_queue.php?round_id={$round->id}&name=$enc_q_name";
                $enc_url = html_safe($url);
                echo "<li><a href='$enc_url'>$q_name</a></li>\n";
            }
        }
        if ($n_queues == 0) {
            echo "<li><i>" . pgettext("no queues", "none") . "</i></li>\n";
        }
        echo "</ul>\n";
    }

    if (project_has_a_hold_in_state($project->projectid, $project->state)) {
        echo "<p>", _("However, this project is currently being held-in-waiting; it will not be auto-released until the hold is removed."), "</p>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_event_subscriptions()
{
    global $project, $code_url, $pguser;

    $projectid = $project->projectid;

    echo "<h2 id='event_subscriptions'>", _("Event Subscriptions"), "</h2>\n";

    echo "<div>\n";

    $user_email_address = get_forum_email_address($pguser);
    echo "<p>";
    echo _("Here you can sign up to be notified when certain events happen to this project.");
    echo "\n";
    echo sprintf(
        _("Notifications will be sent to your email address, which is currently &lt;%1\$s&gt;. (If this is not correct, please visit <a href='%2\$s'>your profile</a>.)"),
        $user_email_address,
        get_url_to_edit_profile()
    );
    echo "\n";
    echo _("Your current subscriptions are shown below with a shaded background.");
    echo "</p>\n";

    $n_users_subscribed_to_ = get_n_users_subscribed_to_events_for_project($projectid);

    $url = "$code_url/tools/set_project_event_subs.php";
    echo "<form method='post' action='$url'>\n";
    echo "<input type='hidden' name='projectid' value='$projectid'>\n";
    echo "<table id='event_subscriptions'>\n";
    echo "<tr>";
    echo "<th>", _("Event"), "</th>";
    echo "<th>", _("Subscribed?"), "</th>";
    echo "<th>", _("Users Subscribed"), "</th>";
    echo "</tr>\n";
    $subscribable_project_events = get_subscribable_project_events();
    foreach ($subscribable_project_events as $event => $label) {
        if (!can_user_subscribe_to_project_event($pguser, $projectid, $event)) {
            continue;
        }

        $is_subd = user_is_subscribed_to_project_event($pguser, $projectid, $event);
        $class = ($is_subd ? 'checkbox-cell-selected' : 'checkbox-cell');
        $checked = ($is_subd ? 'checked' : '');
        echo "<tr>";
        echo "<td>$label</td>";
        echo "<td class='$class'><input type='checkbox' name='$event' $checked></td>";
        echo "<td class='center-align'>{$n_users_subscribed_to_[$event]}</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    echo "<input type='submit' value='", attr_safe(_("Update Event Subscriptions")), "'>\n";
    echo "</form>\n";

    echo "</div>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_holds()
// Display a project's current holds, and allow authorized users to change them.
{
    global $project, $code_url;

    echo "<h2 id='holds'>", _("Project Holds"), "</h2>\n";

    echo "<div>\n";

    echo "<p>", _("Each <b>hold</b> is characterized by a project state, and prevents the project from undergoing an automatic state transition from that state:"), "</p>\n";
    echo "<ul>\n";
    echo "<li>", _("A hold in a round's <b>Waiting</b> state prevents the project from auto-transitioning to that round's Available state (i.e., prevents it from being auto-released to proofreaders in that round)."), "</li>\n";
    echo "<li>", _("A hold in a round's <b>Available</b> state prevents the project from advancing to the next round or pool."), "</li>\n";
    echo "</ul>\n";
    echo "<p>", _("The project's current holds are shown below with a shaded background."), "</p>";

    $current_hold_states = $project->get_hold_states();

    $url = "$code_url/tools/set_project_holds.php";
    if ($project->can_be_managed_by_current_user) {
        echo "<form method='post' action='$url'>\n";
        echo "<input type='hidden' name='projectid' value='{$project->projectid}'>\n";
        echo "<input type='hidden' name='return_uri' value='" . urlencode($_SERVER['REQUEST_URI']) . "#holds'>\n";
    }

    echo "<table id='project_holds'>\n";
    echo "<tr>\n";
    echo "<th></th>\n";
    echo "<th>", _("hold in Waiting"), "</th>\n";
    echo "<th>", _("hold in Available"), "</th>\n";
    echo "</tr>\n";

    $disabled = $project->can_be_managed_by_current_user ? '' : 'disabled';
    foreach (array_chunk(Project::get_holdable_states(), 2) as $set => $states) {
        [$round_id] = explode(".", $states[0]);
        echo "<tr>\n";
        echo "<th>", $round_id, "</th>\n";
        foreach ($states as $state) {
            $is_a_current_hold_state = in_array($state, $current_hold_states);
            $class = ($is_a_current_hold_state ? 'checkbox-cell-selected' : 'checkbox-cell');
            $checked = ($is_a_current_hold_state ? 'checked' : '');

            echo "<td class='$class'><input type='checkbox' name='$state' $checked $disabled></td>\n";
        }
        echo "</tr>\n";
    }

    echo "</table>\n";
    if ($project->can_be_managed_by_current_user) {
        echo "<input type='submit' value='", attr_safe(_("Update Holds")), "'>\n";
        echo "</form>\n";
    }
    echo "</div>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_history()
{
    global $project;

    echo "<h2>", _("Project History"), "</h2>\n";
    $sql = "
        SELECT timestamp, who, event_type, details1, details2, details3
        FROM project_events
        WHERE projectid = '{$project->projectid}'
        ORDER BY event_id
    ";
    $res = DPDatabase::query($sql);
    $events = [];
    while ($event = mysqli_fetch_assoc($res)) {
        $events[] = $event;
    }

    $events2 = fill_gaps_in_events($events);

    // The project history is only partially translated right now.
    $event_type_labels = [
        "archive" => _("archive"),
        "creation" => _("creation"),
        "deletion" => _("deletion"),
        "edit" => _("edit"),
        "smooth-reading" => _("smooth reading"),
        "transition" => _("transition"),
        "transition(s)" => _("transition(s)"),
        "add_holds" => _("add hold(s)"),
        "remove_holds" => _("remove hold(s)"),
    ];

    // this table has 6 columns
    echo "<table class='basic'>\n";
    foreach ($events2 as $event) {
        echo "<tr>\n";

        echo "<td>";
        echo(
            $event['timestamp'] == '?'
            ? '?'
            : date('Y-m-d H:i:s', $event['timestamp'])
        );
        echo "</td>\n";

        echo "<td>{$event['who']}</td>\n";

        $event_type = $event['event_type'];
        echo "<td>",
        array_get($event_type_labels, $event_type, $event_type),
        "</td>\n";
        // count columns so we can fill up space after
        $spare_cols = 3;

        if ($event_type == 'transition' || $event_type == 'transition(s)') {
            $from_state = $event['details1'];
            $from_state_t = (
                $from_state == '?'
                ? _('unknown state')
                : get_medium_label_for_project_state($from_state)
            );
            if ($from_state_t == '') {
                $from_state_t = $from_state;
            }

            $to_state = $event['details2'];
            $to_state_t = (
                $to_state == '?'
                ? _('unknown state')
                : get_medium_label_for_project_state($to_state)
            );
            if ($to_state_t == '') {
                $to_state_t = $to_state;
            }

            $details3 = $event['details3'];
            $details3_t = $details3; // but that can be overridden by ...
            $mappings = [
                '/^via_q:\s*$/' => _('via no queue'),
                '/^via_q:\s*(.*)/' => _('via "%s" queue'),
                '/^out_to:\s*(.*)/' => _('checked out to %s'),
            ];
            foreach ($mappings as $pattern => $format) {
                if (preg_match($pattern, $details3, $matches)) {
                    unset($matches[0]); // the (sub)string that matched the whole pattern
                    $details3_t = vsprintf($format, $matches);
                    break;
                }
            }
            echo "<td>", sprintf(_("from %s"), $from_state_t), "</td>\n";
            echo "<td>", sprintf(_("to %s"), $to_state_t), "</td>\n";
            echo "<td>$details3_t</td>\n";
            $spare_cols = 0;
        } elseif ($event_type == 'smooth-reading') {
            echo "<td>{$event['details1']}</td>\n";
            $spare_cols = 2;
            if (($event['details1'] == 'text available') || ($event['details1'] == 'deadline extended')) {
                $deadline_f = date('Y-m-d H:i:s', $event['details2']);
                echo "<td>until $deadline_f</td>\n";
                $spare_cols = 1;
            }
        } elseif ($event_type == 'edit') {
            $changed_fields = $event['details1'];

            if ($changed_fields == '') {
                // This is an old edit event,
                // from before we recorded changed fields.
                // Just leave the rest of the row blank.
            } else {
                // List the changed fields (localized for the current user).

                if ($changed_fields == 'NONE') {
                    // TRANSLATORS: i.e. no fields changed
                    $list_of_changed_fields = pgettext("no fields", "none");
                } else {
                    $label_for_project_field_ = Project::get_field_labels();

                    $labels = [];
                    foreach (explode(' ', $changed_fields) as $fieldname) {
                        $labels[] = html_safe(array_get($label_for_project_field_, $fieldname, $fieldname));
                    }
                    // Note that this lists the changed fields in the same order
                    // as they appear in the 'details1' field of the events table,
                    // which isn't necessarily consistent (or logical).
                    // However, I'm not sure it's worth doing anything about that.

                    if (count($labels) == 0) { /** @phpstan-ignore-line Fixed in https://github.com/phpstan/phpstan/issues/9403 */
                        // This shouldn't happen.
                        // TRANSLATORS: i.e. no fields changed
                        $list_of_changed_fields = pgettext("no fields", "none");
                    } else {
                        $list_of_changed_fields = implode(', ', $labels);
                    }
                }
                echo "<td colspan='3'>";
                echo _("Changed fields:");
                echo " ", $list_of_changed_fields;
                echo "</td>\n";
                $spare_cols = 0;
            }
        } elseif ($event_type == 'add_holds' || $event_type == 'remove_holds') {
            $state_labels = [];
            foreach (explode(' ', $event['details1']) as $state) {
                $state_labels[] = get_medium_label_for_project_state($state);
            }
            echo "<td colspan='3'>";
            echo join(", ", $state_labels);
            echo "</td>\n";
            $spare_cols = 0;
        }
        if ($spare_cols > 0) {
            echo "<td colspan='$spare_cols'></td>";
        }
        echo "</tr>\n";
    }
    echo "</table>\n";
}

/**
 * If the project's event-history has gaps, fill them with pseudo-events.
 */
function fill_gaps_in_events($in_events)
{
    $out_events = [];

    // Creation at the start
    if (count($in_events) == 0 || $in_events[0]['event_type'] != 'creation') {
        $pseudo_event = [
            'timestamp' => '?',
            'who' => '?',
            'event_type' => 'creation',
        ];
        $out_events[] = $pseudo_event;
    }

    // Continuity of state
    $running_state = null;
    foreach ($in_events as $event) {
        switch ($event['event_type']) {
            case 'creation':
                $running_state = PROJ_NEW;
                break;

            case 'transition':
                $from_state = $event['details1'];
                $to_state = $event['details2'];
                if ($running_state != $from_state) {
                    $pseudo_event = [
                        'timestamp' => '?',
                        'who' => '?',
                        'event_type' => 'transition(s)',
                        'details1' => $running_state,
                        'details2' => $from_state,
                        'details3' => '',
                    ];
                    $out_events[] = $pseudo_event;
                }
                $running_state = $to_state;
                break;

            case 'deletion':
                $running_state = null;
                break;
        }
        $out_events[] = $event;
    }

    global $project;
    if ($running_state != $project->state) {
        $pseudo_event = [
            'timestamp' => '?',
            'who' => '?',
            'event_type' => 'transition(s)',
            'details1' => $running_state,
            'details2' => $project->state,
            'details3' => '',
        ];
        $out_events[] = $pseudo_event;
    }
    return $out_events;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_images()
{
    global $project;
    global $code_url;

    if (!$project->dir_exists) {
        return;
    }

    $projectid = $project->projectid;

    $link_url = "$code_url/tools/proofers/images_index.php?project=$projectid";

    $link_text = (
        $project->can_be_managed_by_current_user
        ? _("View/Replace Images")
        : _("View Images Online")
    );

    echo "<h2>", _('Images'), "</h2>";
    echo "<ul>";
    echo "<li><a href='$link_url'>$link_text</a></li>\n";
    echo "</ul>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_extra_files()
{
    global $project;

    if (!$project->can_be_managed_by_current_user
        && !$project->PPer_is_current_user
        && !$project->PPVer_is_current_user) {
        return;
    }

    if (!$project->dir_exists) {
        return;
    }

    // Build a list of files in the directory
    $saved_dir = getcwd();
    chdir($project->dir);
    $filenames = array_diff(
        // get all files
        glob("*"),

        // exclude directories
        glob("*", GLOB_ONLYDIR),

        // exclude images
        glob("*.{png,jpg}", GLOB_BRACE),

        // These appear at "Word Lists"
        get_wordcheck_file_names(),

        // These three appear under "Post Downloads"
        [
            $project->projectid . 'images.zip',
            $project->projectid . '.zip',
        ],
    );
    chdir($saved_dir);

    echo "<h2>", _('Extra Files in Project Directory'), "</h2>";
    echo "<ul>";

    foreach ($filenames as $filename) {
        echo "<li><a href='$project->url/$filename'>$filename</a></li>";
    }

    if (count($filenames) == 0) {
        echo "<li><i>", pgettext("no files", "none"), "</i></li>\n";
    }

    echo "</ul>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_post_files()
{
    global $project, $pguser;

    if (!$project->dir_exists && !$project->pages_table_exists) {
        return;
    }

    $projectid = $project->projectid;
    $state = $project->state;

    if (user_can_work_in_stage($pguser, 'PP')) {
        echo "<h2>";
        echo _("Post-Processing Files");
        echo "</h2>\n";

        echo "<ul>";

        echo_download_zip(_("Download Zipped Images"), 'images');
        echo_download_zip(_("Download Zipped Music Files"), '_music');

        if ($state == PROJ_POST_FIRST_AVAILABLE || $state == PROJ_POST_FIRST_CHECKED_OUT) {
            echo_download_zip(_("Download Zipped Text"), '');

            echo "<li>";
            echo_uploaded_zips('_first_in_prog_', _('partially post-processed'));
            echo "</li>";

            if ($project->PPer_is_current_user || user_is_a_sitemanager()) {
                echo_upload_backup($projectid, _("Upload a partially post-processed file as a backup"), "in_prog_1");
            }
        } elseif ($state == PROJ_POST_SECOND_AVAILABLE || $state == PROJ_POST_SECOND_CHECKED_OUT) {
            echo_download_zip(_("Download Zipped Text"), '_second');

            echo "<li>";
            echo_uploaded_zips('_second_in_prog_', _('partially verified'));
            echo "</li>";

            if ($project->PPVer_is_current_user || user_is_a_sitemanager()) {
                echo_upload_backup($projectid, _("Upload a partially verified file as a backup"), "in_prog_2");
            }
        }
    } else {
        echo "<h2>";
        echo _("Concatenated Text Files");
        echo "</h2>\n";

        echo "<ul>";
    }
    // regenerate post files. Only for site managers.
    // or download concatenated text, for all. Do we want to limit
    // this to people allowed to work in PP? If so, we should definitely
    // include the PM of this project.

    global $code_url;

    $sums = [];
    foreach (Rounds::get_all() as $round) {
        $sums[] = "SUM( $round->text_column_name != '' ) AS $round->id";
    }
    $sums = join(", ", $sums);
    $sql = "
            SELECT $sums
            FROM $projectid
    ";
    $res = DPDatabase::query($sql);
    $sums = mysqli_fetch_assoc($res);

    foreach (Rounds::get_all() as $round) {
        if (intval($sums[$round->id]) != 0) {
            $highest_round_id = $round->id;
        }
    }

    echo "<li>";
    if (user_is_a_sitemanager()) {
        echo _("Generate Post-Processing Files (This will overwrite existing post-processing files, if any.)"), "\n";
    } else {
        echo _("Download Concatenated Text"), "\n";
    }
    echo "<form method='post' action='$code_url/tools/project_manager/generate_post_files.php'>\n";
    echo "<input type='hidden' name='projectid' value='$projectid'>\n";

    if (isset($highest_round_id)) {
        echo "<input type='radio' name='round_id' value='[OCR]'>[OCR]&nbsp;\n";
        foreach (Rounds::get_all() as $round) {
            $checked = ($round->id == $highest_round_id ? 'CHECKED' : '');
            echo "<input type='radio' name='round_id' value='$round->id' $checked>$round->id&nbsp;\n";
            if ($round->id == $highest_round_id) {
                break;
            }
        }
    } else {
        echo "<input type='radio' name='round_id' value='[OCR]' CHECKED>[OCR]&nbsp;\n";
    }
    echo "<br>";

    echo _("For each page, use:"), "<br>\n";
    echo "<input type='radio' name='which_text' value='EQ' CHECKED>";
    echo _("the text (if any) saved in the selected round; or"), "<br>\n";
    echo "<input type='radio' name='which_text' value='LE'>";
    echo _("the latest text saved in any round up to and including the selected round."), "<br>\n";
    echo _("(If every page has been saved in the selected round, then the two choices are equivalent.)"), "<br>\n";

    // Proofreader names allowed for people who can see proofreader names
    // on the page details
    if ($project->names_can_be_seen_by_current_user) {
        echo _("Include usernames?"), " &nbsp;&nbsp; ";
        echo "<input type='radio' name='include_proofers' value='1'>";
        echo _("Yes"), " &nbsp;&nbsp; ";
        echo "<input type='radio' name='include_proofers' value='0' CHECKED>";
        echo _("No"), "<br>\n";
    } else {
        echo "<input type='hidden' name='include_proofers' value='0'>";
    }

    // saving files allowed only for sitemanagers
    if (user_is_a_sitemanager()) {
        echo _("Save file on server?"), "  &nbsp;&nbsp; ";
        echo "<input type='radio' name='save_files' value='1' CHECKED>";
        echo _("Yes"), " &nbsp;&nbsp; ";
        echo "<input type='radio' name='save_files' value='0'>";
        echo _("No"), "<br>\n";

        echo "<input type='submit' value='", attr_safe(_("(Re)generate")), "'>\n";
    } else {
        echo "<input type='hidden' name='save_files' value='0'>";
        echo "<input type='submit' value='", attr_safe(_("Download")), "'>\n";
    }

    echo "</form>\n";
    echo "</li>";

    echo "</ul>\n";
}

// -----------------------------------------------------------------------------
function echo_upload_backup($projectid, $text, $stage)
{
    global $code_url;
    echo "<li class='spaced'>";
    echo "<a href='$code_url/tools/upload_text.php?project=$projectid&stage=$stage'>$text</a>";
    echo "</li>";
}

// -----------------------------------------------------------------------------
function echo_uploaded_zips($discriminator, $upload_type)
{
    global $project;

    $done_files = glob("$project->dir/*".$discriminator."*.zip");
    if ($done_files) {
        // TRANSLATORS: %s is an adjective like "partially verified" or "smoothread"
        echo sprintf(_("Download %s file uploaded by:"), $upload_type);
        echo "<ul>";
        foreach ($done_files as $filename) {
            $showname = basename($filename, ".zip");
            $showname = substr($showname, strpos($showname, $discriminator) + strlen($discriminator));
            echo_download_zip($showname, $discriminator.$showname);
        }
        echo "</ul>";
    } else {
        // TRANSLATORS: %s is an adjective like "partially verified" or "smoothread"
        echo sprintf(_("No %s results have been uploaded."), $upload_type);
    }
}
// -----------------------------------------------------------------------------

function echo_download_zip($link_text, $discriminator)
{
    global $project, $code_url;

    $projectid = $project->projectid;
    if ($discriminator == 'images') {
        // Generate images zip on the fly,
        // so it's not taking up space on the disk.

        $url = "$code_url/tools/download_images.php?projectid=$projectid&amp;dummy={$projectid}images.zip";
        // The 'dummy' parameter is for the benefit of download-software that
        // names the resulting file after the last component of the request URL.

        // Images don't compress much in a zip,
        // so the sum of their individual filesizes
        // is a fair approximation (and hopefully an upper bound)
        // of the size of the resulting zip.
        $filesize_b = 0;
        foreach (glob("$project->dir/*.{png,jpg}", GLOB_BRACE) as $image_path) {
            $filesize_b += filesize($image_path);
        }
        $last_modified = null;
    } else {
        $p = "$projectid$discriminator.zip";

        if (!file_exists("$project->dir/$p")) {
            if ($discriminator == '_music') {
                // don't show any link
                return;
            }
            // The file doesn't exist yet, so there's no point providing a link to it.
            echo "<li>";
            echo $link_text;
            echo " (";
            echo _("File has not been generated yet.");
            echo ")";
            echo "</li>";
            echo "\n";
            return;
        }

        $url = "$project->url/$p";
        $filesize_b = filesize("$project->dir/$p");
        $last_modified = filemtime("$project->dir/$p");
    }

    echo "<li>";
    echo "<a href='$url'>";
    echo $link_text;
    echo "</a>";
    echo_byte_size($filesize_b);
    echo_last_modified($last_modified);
    echo "</li>";
    echo "\n";
}

function echo_last_modified($last_modified)
{
    if (isset($last_modified)) {
        echo " (", icu_date_template("long+time", $last_modified), ")";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_postcomments()
{
    global $project, $code_url;

    if ($project->state != PROJ_POST_FIRST_CHECKED_OUT) {
        return;
    }

    $projectid = $project->projectid;

    if ($project->PPer_is_current_user) {
        echo "<h2>" . _("Post-Processor's Comments") . "</h2>";

        echo_postcomments_instructions();

        echo "<form name='pp_update' method='post' action='$code_url/tools/post_proofers/postcomments.php'>\n";
        echo "<textarea name='postcomments' cols='60' rows='6'>\n";
        echo html_safe($project->postcomments);
        echo "</textarea>\n";
        echo "<input type='hidden' name='projectid' value='$projectid'>\n";
        echo "<br><input type='submit' value='" . attr_safe(_('Update comment and project status')) . "'>";
        echo "</form>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_smooth_reading()
{
    global $project, $code_url, $pguser;

    if ($project->state != PROJ_POST_FIRST_CHECKED_OUT) {
        return;
    }

    $projectid = $project->projectid;

    $current_user_can_manage_SR_for_this_project =
        $project->PPer_is_current_user || user_is_a_sitemanager();
    // i.e., can:
    // -- make the project available for SR (initially or again),
    // -- replace the SR-able text,
    // -- see SR-commitments, and
    // -- read SR'ed texts

    echo "<h2 class='thin-heading' id='smooth_start'>", _('Smooth Reading'), "</h2>";

    if ($project->smoothread_deadline == 0) {
        echo "<p class='thin-para'>", _('This project has not been made available for Smooth Reading.'), "</p>";

        if ($current_user_can_manage_SR_for_this_project) {
            echo "<ul class='list-head'>";
            echo "<li>";
            $label = _('Make it available for %1$s days (between %2$d and %3$d).');
            sr_echo_time_form($label, 7, 42, 21, false);
            echo "</li>\n";
            echo "</ul>";
        }
    } else {
        // Project has been made available for SR
        if ($project->is_available_for_smoothreading()) {
            $sr_deadline_str = icu_date_template("long+time", $project->smoothread_deadline);
            $sr_sentence = sprintf(
                _('This project has been made available for Smooth Reading until %s server time.'),
                "<b>$sr_deadline_str</b>"
            );

            echo "<p class='thin-para'>$sr_sentence</p>";

            if ($current_user_can_manage_SR_for_this_project) {
                echo "<ul class='list-head'>";
                echo "<li>";
                $label = _('Extend Smooth Reading deadline by %1$s day(s) (between %2$d and %3$d).');
                sr_echo_time_form($label, 1, 42, 1, true);
                echo "</li>";
                echo "<li class='list-head'>";
                echo "<a href='$code_url/tools/upload_text.php?project=$projectid&stage=smooth_avail'>";
                echo _("Replace the currently available Smooth Reading file.");
                echo "</a>";
                echo "</li>";
                echo "</ul>";
            }

            echo "<ul class='list-head'>";
            echo_smoothreading_options($project);
            echo "</ul>";

            if (!$project->PPer_is_current_user) {
                echo "<ul class='list-head'>";
                // We don't allow guests to upload the results of smooth-reading.
                global $user_is_logged_in;
                if ($user_is_logged_in) {
                    echo "<li class='list-head'>";
                    echo "<a href='$code_url/tools/upload_text.php?project=$projectid&stage=smooth_done'>";
                    echo _("Upload a Smooth Read report") ;
                    echo "</a>";
                    echo "</li>\n";
                    // The upload does not cause the project to change state --
                    // it's still checked out to PPer.

                    if (!sr_user_is_committed($projectid, $pguser)) {
                        echo "<li class='list-head'>";
                        echo _('Volunteer to Smooth Read this project for the PPer by pressing:');
                        sr_echo_commitment_form($projectid);
                        echo "</li>\n";
                    } else {
                        echo "<li class='list-head'>";
                        echo _('You have volunteered to Smooth Read this project. If you wish to withdraw from Smooth Reading it, please press:');
                        sr_echo_withdrawal_form($projectid);
                        echo "</li>";
                    }
                } else {
                    echo "<li>";
                    echo _('Please note that while unregistered guests are welcome to download texts for Smooth Reading, only registered volunteers are able to upload annotated texts.');
                    echo "\n";
                    echo _('A registration link is available at the top of this page.');
                    echo "</li>\n";
                }
                echo "</ul>";
            }
        } else {
            echo "<p class='thin-para'>", _('The Smooth Reading deadline for this project has passed.'), "</p>\n";

            if ($current_user_can_manage_SR_for_this_project) {
                echo "<ul class='list-head'>";
                echo "<li>";
                $label = _('Make it available again for %1$s days (between %2$d and %3$d).');
                sr_echo_time_form($label, 7, 42, 21, false);
                echo "</li>\n";
                echo "</ul>";
            }
        }

        if ($current_user_can_manage_SR_for_this_project) {
            $sr_list = sr_get_committed_users($projectid);

            echo "<ul class='list-head'>";
            echo "<li class='list-head'>";
            if (count($sr_list) == 0) {
                echo _('No one has volunteered to Smooth Read this project.');
            } else {
                echo _('The following users have volunteered to Smooth Read this project:');
                echo "<ul>";
                foreach ($sr_list as $sr_user) {
                    $user_privmsg_url = get_url_to_compose_message_to_user($sr_user);
                    echo "<li>";
                    echo "<a href='$user_privmsg_url'>$sr_user</a>";
                    echo "</li>\n";
                }
                echo "</ul>\n";
            }
            echo "</li>\n";

            echo "<li>";
            // TRANSLATORS: Should be translated as past tense.
            echo_uploaded_zips('_smooth_done_', _('Smooth Read'));
            echo "</li>";
            echo "</ul>";
        }
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function sr_echo_time_form($label, $min_days, $max_days, $default_days, $extend = false)
{
    // $label is an sprintf formatting string with three placement values:
    // * %1$s will be replaced with a form field for number-of-days input
    // * %2$d is the minimum number of days available to the form
    // * %3$d is the maximum number of days available to the form
    // the $extend parameter could be thought unnecessary since it correlates
    // with (deaddline > now) but if the page is stale an extension request could be made
    // after the deadline has passed which we should warn about.
    global $code_url, $project;

    $action = $extend ? "$code_url/tools/extend_sr.php" : "$code_url/tools/upload_text.php";

    echo "<form method='GET' action='$action'>";
    echo "<input type='hidden' name='project'  value='{$project->projectid}'>\n";
    echo "<input type='hidden' name='stage' value='smooth_avail'>\n";
    $day_input = "&nbsp;<input type='number' name='days' min='$min_days' max='$max_days' style='width: 4em;' value='$default_days'>";
    echo sprintf($label, $day_input, $min_days, $max_days), "&nbsp;<input type='submit' value='", attr_safe(_("Go")), "'>\n";
    echo "</form>\n";
}

function echo_smoothreading_options($project)
{
    $smooth_dir = "$project->dir/smooth";
    $smooth_url = "$project->url/smooth";
    echo "<li class='list-head'>", _("Download a Smooth Reading file");
    echo "<ul>";
    echo_file_downloads(glob("$smooth_dir/*.txt"), $smooth_url);

    // zipped htm(l) file
    foreach (glob("$smooth_dir/*.zip") as $zip_file) {
        $base_name = basename($zip_file);
        $extra_text = ": " . _('HTML with any images');
        echo_download_item($smooth_url, $zip_file, $base_name, $base_name, $extra_text);
    }

    // no space after bracket commas
    echo_file_downloads(glob("$smooth_dir/*.{epub,mobi}", GLOB_BRACE), $smooth_url);

    // original uploaded zip file
    $file_base_name = $project->projectid . "_smooth_avail.zip";
    $file = "$project->dir/$file_base_name";
    if (file_exists($file)) {
        echo_download_item($project->url, $file, $file_base_name, "Download all formats");
    }
    echo "</ul>";
    echo "</li>";

    $file_bases = [];
    $files = glob("$smooth_dir/*.{txt,htm,html}", GLOB_BRACE);
    foreach ($files as $file) {
        $file_bases[] = basename($file);
    }

    // if html files and image dirs were zipped in directories, find them
    $dirs = glob("$smooth_dir/*", GLOB_ONLYDIR);
    foreach ($dirs as $dir) {
        $subfiles = glob("$dir/*.{htm,html}", GLOB_BRACE);
        foreach ($subfiles as $subfile) {
            $file_bases[] = basename($dir) . "/" . basename($subfile);
        }
    }
    // if sr file uploaded before transition to this mode there will not be a smooth directory
    if ($file_bases) {
        echo "<li class='list-head'>", _("Open in browser (for reading only -- no annotations can be made):");
        echo "<ul>";
        foreach ($file_bases as $file_base) {
            $url = "$smooth_url/$file_base";
            echo "<li>";
            echo "<a href='$url'>", html_safe($file_base), "</a>";
            echo "</li>\n";
        }
        echo "</ul>";
        echo "</li>";
    }
}

function echo_file_downloads($files, $smooth_url)
{
    foreach ($files as $file) {
        // filename with extension
        $file_base_name = basename($file);
        echo_download_item($smooth_url, $file, $file_base_name, $file_base_name);
    }
}

function echo_download_item($url, $file, $file_base_name, $link_text, $extra_text = "")
{
    $url = "$url/$file_base_name";
    echo "<li>";
    echo "<a href='$url' download='", attr_safe($file_base_name), "'>", html_safe($link_text), "</a>", html_safe($extra_text);
    echo_byte_size(filesize($file));
    echo "</li>\n";
}

function echo_byte_size($size)
{
    echo " (", humanize_bytes($size), ")";
}

function do_ppv_report()
{
    global $project, $code_url;

    if ($project->state != PROJ_POST_SECOND_CHECKED_OUT) {
        return;
    }

    if (!$project->PPVer_is_current_user) {
        return;
    }

    $url = "$code_url/tools/post_proofers/ppv_report.php?project={$project->projectid}";
    echo "<p><a href='$url'>", _("Submit a PPV Summary for this project"), "</a></p>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_change_state()
{
    global $project, $pguser, $code_url, $charset;

    $valid_transitions = get_valid_transitions($project, $pguser);
    if (count($valid_transitions) == 0) {
        return;
    }

    echo "<h2>";
    echo _("Change Project State");
    echo "</h2>\n";

    if ($project->state == PROJ_NEW) {
        echo "<p>\n";
        echo _("Check for missing pages and make sure that all illustration files have been uploaded <b>before</b> moving this project into the rounds.");
        echo "</p>\n";
    }

    // print out a message if PM has project loads disabled,
    // as they can't move a project out of the new project
    // or unavailable state
    if ($project->can_be_managed_by_current_user) {
        check_user_can_load_projects(false);
    }

    $here = $_SERVER['REQUEST_URI'];
    // If the request URI included an 'expected_state' parameter, there's a wrinkle:
    // If the user clicks on this button, the project's state will (normally) change.
    // So if we then returned the user to exactly this URI, they'd get a warning:
    // "The project is no longer in 'this state'. It is now in 'that state'.
    // So we suppress the 'expected_state' parameter from the request URI.
    $here = preg_replace('/expected_state=[A-Za-z._0-9]+/', '', $here);
    // This can leave an extra &, but I suspect browsers can handle it.

    foreach ($valid_transitions as $transition) {
        // Used for disabling the button
        $optional_btn_attr = "";

        // By default say who is allowed to do this transition.
        $text_next_to_btn = " [$transition->who_restriction]";

        if ($transition->is_disabled($project)) {
            $optional_btn_attr = "disabled";

            // Gray out the original text and add an explanation why it is disabled
            $reason = $transition->why_disabled($project);
            $text_next_to_btn = "<span class='transition-disabled'>$text_next_to_btn</span> [$reason]";
        }

        echo "<form method='POST' action='$code_url/tools/changestate.php'>";
        echo "<input type='hidden' name='projectid'  value='{$project->projectid}'>\n";
        echo "<input type='hidden' name='curr_state' value='{$project->state}'>\n";
        echo "<input type='hidden' name='next_state' value='{$transition->to_state}'>\n";
        echo "<input type='hidden' name='confirmed'  value='yes'>\n";
        echo "<input type='hidden' name='return_uri' value='", attr_safe($here), "'>\n";

        $question = $transition->confirmation_question;
        if (is_null($question)) {
            $onClick_condition = "return true;";
        } else {
            $onClick_condition = "return confirm(\""
                . javascript_safe($question, $charset) . "\");";
        }
        $onclick_attr = "onClick='$onClick_condition'";
        echo "<input type='submit' value='", attr_safe($transition->action_name), "' $onclick_attr $optional_btn_attr>";
        echo $text_next_to_btn;

        echo "</form>\n";
        echo "<br>";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_page_summary()
{
    global $project;
    $projectid = $project->projectid;

    if (!$project->pages_table_exists) {
        return;
    }

    echo "<h2>"._("Page Summary")."</h2>\n";

    // page counts by state.
    $total_num_pages = $project->get_num_pages();

    echo "<table>\n";
    foreach ($project->get_page_states() as $page_state) {
        $num_pages = $project->get_num_pages_in_state($page_state);
        if ($num_pages != 0) {
            // TRANSLATORS: %s is a page state, this is a label in a table for the number of pages in this state
            echo "<tr><td class='right-align'>$num_pages</td><td>".sprintf(_("in %s"), $page_state)."</td></tr>\n";
        }
    }
    echo "<tr><td colspan='2'><hr></td></tr>\n";
    echo "<tr><td class='right-align'>$total_num_pages</td><td>"._("Pages Total")."</td></tr>\n";
    echo "</table>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_page_table()
{
    global $project;

    if (!$project->pages_table_exists) {
        return;
    }

    {
        echo_detail_legend();
        echo "<p>" . _("It is <b>strongly</b> recommended that you view page differentials by right-clicking on a diff link and opening the link in a new window or tab.") . "</p>";

        // second arg. indicates to show size of image files.
        echo_page_table($project, 1);
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/**
 * Echo the post-processor comments block.
 *
 * If a project is checked out for PP, and the post-processor is viewing
 * the project page, then the page will have an editable text area
 * entitled "Post-Processor's Comments".
 *
 * This function is called between the title and the text area,
 * and should give (site-specific) instructions to the post-processor
 * on how to use (or not use) the postcomments field.
 */
function echo_postcomments_instructions()
{
    global $wiki_url;

    echo '<p>' .
        _("You can use this text area to enter comments on how you're
        doing with the post-processing, both to keep track for yourself
        and so that we will know that there's still work in progress.")
        . '</p>';
    echo '<p>' . sprintf(
        // TRANSLATORS: PM = project manager
        _("If you find that some proofing images are missing or unreadable,
        or that illustration images are missing or inadequate,
        first let the PM know what you've found (or not found).
        If you don't hear back from the PM within a reasonable amount of time,
        let the <a href='%s'>db-req</a> squirrels know about the issues
        so we can get the project fixed."),
        "$wiki_url/Db-req"
    )
        . '</p>';
    echo '<p>'
        . _("Note that your old comments will be replaced by those you enter here.")
        . '</p>';
}

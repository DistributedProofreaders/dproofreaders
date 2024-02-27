<?php
include_once($relPath.'page_tally.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'graph_data.inc'); // get_round_backlog_stats()

/*
 * This file deals with the gradual revelation of site features,
 * based on the number of pages proofread by the user.
 * (So far, it only has the code that is shared between multiple files.
 * Maybe it should include unshared stuff too, for completeness.)
 *
 * The following are common thresholds used in this and other files
 * based on the number of pages proofread:
 *   <  20 - newbie user, gets simplified information
 *   < 100 - proofreader is guided through the site
 *   < 300 - final guidance
 */


/**
 * Retrieve the number of pages proofread by the current user.
 *
 * For demo purposes, allow the user (via the URL parameter 'numofpages')
 * to pretend to have a different number of pages,
 * as long as it's less than their actual number.
 */
function get_pages_proofed_maybe_simulated($username = null)
{
    if ($username === null) {
        $username = User::current_username();
    }

    $pagesproofed = user_get_ELR_page_tally($username);
    $pagesproofed = get_integer_param($_GET, 'numofpages', $pagesproofed, 0, $pagesproofed);

    return $pagesproofed;
}

function welcome_see_beginner_forum($pagesproofed, $page_id, $username = null)
{
    global $code_url, $ELR_round, $beginners_site_forum_idx;

    if ($username === null) {
        $username = User::current_username();
    }

    // If the ELR_round requires access and the user doesn't currently
    // have it, show them what they need to do.
    $accessible_stages = get_stages_user_can_work_in($username);
    if (!isset($accessible_stages[$ELR_round->id])) {
        $uao = $ELR_round->user_access($username);

        // If the user has been denied access, just return and don't show anything
        if ($uao->request_status == 'sat-denied' ||
            $uao->request_status == 'unsat-denied') {
            return;
        }

        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo _("Welcome New Proofreader!");
        echo "</div>";

        echo "<p>";
        echo sprintf(_('Before you can begin proofreading, you must have:'));
        echo "<ul>";
        foreach ($uao->minima_table as $row) {
            [$criterion_str, $minimum, $user_score, $satisfied] = $row;
            echo "<li>$criterion_str</li>";
        }
        echo "</ul>\n";
        echo "</p>";

        echo "<p>";
        echo sprintf(_('After that you will be able to work in <a href="%1$s">%2$s</a>, the first proofreading round.'), "$code_url/{$ELR_round->relative_url}", $ELR_round->id);
        echo "</p>";
        echo "</div>";
    } elseif ($pagesproofed < 100) {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo _("Welcome New Proofreader!");
        echo "</div>";

        // If the user is not on the entry level round page, direct
        // them to My Suggestions or that round's page.
        if ($page_id != $ELR_round->id) {
            echo "<p>";
            echo sprintf(_('To start proofreading, see the list of projects on the <a href="%1$s">My Suggestions</a> page or the <a href="%2$s">%3$s</a> round page.'), "$code_url/tools/proofers/my_suggestions.php", "$code_url/{$ELR_round->relative_url}", $ELR_round->id);
            echo "</p>";
        }
        // If they are on the entry level round page, let them know that here
        // is a good place to start.
        else {
            echo "<p>";
            // We explicitly don't include a jump to the list of projects in
            // the text to make them scroll down pass the simple proofreading
            // guidelines.
            echo _("This page is where you can find a project to start proofreading. Choose a title from the list of projects at the bottom of this page.");
            echo "</p>";

            echo "<p>";
            echo _("It's best to select a \"BEGINNERS ONLY\" project if one is available in a language in which you are proficient. These projects have been specially prepared to teach you our style of proofreading. You can expect to receive feedback from a mentor on pages you proofread in BEGINNERS ONLY projects. This feedback will likely come at least a few days after you have completed the pages.");
            echo "</p>";
        }
        echo "<p>";
        echo sprintf(_("Please see our <a href='%s'>Beginner's Forum</a> for answers to common questions."), get_url_to_view_forum($beginners_site_forum_idx));
        echo "</p>";

        echo "<p><small>";
        echo _("After a period of time, this message will no longer appear.");
        echo "</small></p>";
        echo "</div>";
    }
}

function thoughts_re_mentor_feedback($pagesproofed)
{
    if ($pagesproofed >= 20 && $pagesproofed < 300) {
        echo "<p>";
        echo sprintf(_("New Proofreaders: <a href='%s'>What did you think of the Mentor feedback you received?</a>"), get_url_to_view_topic(6651));
        echo "</p>";
    }
}

function alert_re_unread_messages($pagesproofed)
{
    global $pguser;

    if ($pagesproofed < 300) {
        $numofPMs = get_number_of_unread_messages($pguser);
        if ($numofPMs > 0) {
            echo "<div class='callout'>";
            echo "<div class='calloutheader'>";
            echo _("You have received a private message in your Inbox.");
            echo "</div>";

            echo "<p>";
            echo _("This could be from somebody sending you feedback on some of the pages you had proofread earlier. We strongly recommend you <b>read</b> your messages. In the links at the top of this page, there is one that says Inbox. Just click on that to open your Inbox.");
            echo "</p>";

            echo "<p><small>";
            echo _("After a period of time, this message will no longer appear.");
            echo "</small></p>";
            echo "</div>";
        }
    }
}

function maybe_output_new_proofer_message()
{
    global $code_url, $ELR_round;

    // Help direct new proofers to projects to proof.
    $pagesproofed = get_pages_proofed_maybe_simulated();
    if ($pagesproofed < 100) {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo _("Looking for projects to proofread?");
        echo "</div>";

        echo "<p>" . sprintf(_('If you\'re looking for projects to proofread, see the list of projects on the <a href="%1$s">My Suggestions</a> page or the <a href="%2$s">%3$s</a> round page.'), "$code_url/tools/proofers/my_suggestions.php", "$code_url/{$ELR_round->relative_url}", $ELR_round->id) . "</p>";

        echo "<p><small>";
        echo _("After a period of time, this message will no longer appear.");
        echo "</small></p>";
        echo "</div>";
    }
}

/**
 * Help direct new proofers to projects to proof.
 */
function maybe_output_new_proofer_project_message($project)
{
    global $code_url, $ELR_round;

    // If the user isn't logged in, bail
    if (User::current_username() === null) {
        return;
    }

    // If this project isn't in the ELR round, bail
    $round = get_Round_for_project_state($project->state);
    if ($round != $ELR_round) {
        return;
    }

    // If this project isn't available for proofreading, bail
    if ($project->state != $round->project_available_state) {
        return;
    }

    // If the user has been denied access, bail
    $uao = $ELR_round->user_access(User::current_username());
    if ($uao->request_status == 'sat-denied' ||
        $uao->request_status == 'unsat-denied') {
        return;
    }

    $accessible_stages = get_stages_user_can_work_in(User::current_username());
    $pagesproofed = get_pages_proofed_maybe_simulated();

    if (!isset($accessible_stages[$ELR_round->id])) {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo _("Welcome New Proofreader!");
        echo "</div>";

        echo "<p>";
        echo sprintf(_('Before you can begin proofreading, you must have:'));
        echo "<ul>";
        foreach ($uao->minima_table as $row) {
            [$criterion_str, $minimum, $user_score, $satisfied] = $row;
            echo "<li>$criterion_str</li>";
        }
        echo "</ul>\n";
        echo "</p>";
        echo "</div>";
    } elseif ($pagesproofed < 100) {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo _("Welcome New Proofreader!");
        echo "</div>";

        echo "<p>";
        echo sprintf(_("You are a click away from proofreading a page. Take a moment to scroll down and read the Project Comments which contain instructions specific to this project. Once you're ready to start proofreading, click the '%s' link."), _("Start Proofreading"));
        echo "<p>";

        echo "<p>";
        echo _("If you have questions, please don't hesitate to ask them in the project's discussion topic in the forums, accessible from a link in the table below.");
        echo "</p>";

        echo "<p>";
        echo _("Happy proofreading!");
        echo "</p>";

        echo "<p><small>";
        echo _("After a period of time, this message will no longer appear.");
        echo "</small></p>";
        echo "</div>";
    }
}

/*
 * Output a callout and encourage the user to work in the round with the
 * highest backlog that they can work in.
 */
function encourage_highest_round($username, $round_id = null)
{
    global $code_url, $ELR_round, $ACCESS_CRITERIA;

    // This URL fragment gets used a lot in this file
    $round_url = "$code_url/tools/proofers/round.php?round_id=";

    // get the backlog stats for all rounds sorted largest first
    $backlog_stats = get_round_backlog_stats(Rounds::get_ids());
    arsort($backlog_stats);

    // get all the rounds a user can work
    $rounds_can_work_in = array_keys(get_stages_user_can_work_in($username));

    // loop through the needs, highest to lowest, and point them to the
    // one where they can make the most impact with their current access
    foreach (array_keys($backlog_stats) as $round_target) {
        $round = get_Round_for_round_id($round_target);

        // don't encourage people towards $ELR_round, as that isn't really
        // helpful for most sites
        if ($round->id == $ELR_round->id) {
            continue;
        }

        // if $round_id is the current one, ignore $round_id because that means
        // we're on that round's page already
        if ($round_target == $round_id) {
            return;
        }

        // prepare the common wordings between the callouts
        $please_move_up = sprintf(
            _("Please consider proofreading a project in $round_target. Having eligible users working in <a href='%s'>%s</a> helps significantly with our backlog."),
            "$round_url$round_target",
            $round_target
        );

        // They can work in the target round, encourage them to do so.
        if (in_array($round_target, $rounds_can_work_in)) {
            // To encourage, but not annoy, users we allow them to mute the
            // message for this round for some number of days.
            $mute_query_param = "mute-encourage";
            $mute_days = 90;

            // check if they've muted the callout for this round
            $user_settings = new Settings($username);
            $mute_setting_key = "mute-encourage-$round_target-expire";
            $mute_expire = $user_settings->get_value($mute_setting_key);

            // if user has requested muting for this round, set the expire time
            if (array_get($_GET, $mute_query_param, "") == $round_target) {
                $mute_expire = time() + 60 * 60 * 24 * $mute_days;
                $user_settings->set_value($mute_setting_key, $mute_expire);
            }

            // user does not have round muted
            if ($mute_expire === null || $mute_expire < time()) {
                if ($mute_expire) {
                    $user_settings->remove_value($mute_setting_key, $mute_expire);
                }
                echo "<div class='callout'>";
                echo "<div class='calloutheader'>";
                echo sprintf(_("Consider working on projects in %s!"), $round_target);
                echo "</div>";

                echo "<p>$please_move_up</p>";

                // build the URL to refer back to the same page
                $url = "?$mute_query_param=$round_target";
                if ($_SERVER["QUERY_STRING"]) {
                    $url .= "&" . $_SERVER["QUERY_STRING"];
                }
                echo "<p><small><a href='" . attr_safe($url) . "'>";
                echo sprintf(_("Mute this message for %s days."), $mute_days);
                echo "</a></small></p>";

                echo "</div>";
            }
            return;
        }

        // They're already eligible but have not requested access, tell them.
        $uao = $round->user_access($username);
        if ($uao->all_minima_satisfied) {
            echo "<div class='callout'>";
            echo "<div class='calloutheader'>";
            echo sprintf(_("You are eligible to work in %s!"), $round_target);
            echo "</div>";

            echo "<p>";
            echo _("You are eligible to work on projects in $round_target!") . " ";
            if ($round->after_satisfying_minima == 'REQ-AUTO') {
                echo sprintf(
                    _("Simply go to the <a href='%s'>%s</a> page to be automatically granted access, find a project that interests you, and start proofreading."),
                    "$round_url$round_target",
                    $round_target
                );
            } else {
                echo sprintf(
                    _("View the round's <a href='%s'>entrance requirements</a> for further details."),
                    "$round_url$round_target#Entrance_Requirements",
                    $round_target
                );
            }
            echo "</p>";
            echo "<p>$please_move_up</p>";
            echo "</div>";
            return;
        }

        // They aren't yet eligible, but if they've satisfied all the criteria
        // except the quizzes (eg number of days and pages done), nudge them along.
        $ready_ignoring_quizzes = true;
        $quiz_requirements = [];
        $uao = $round->user_access($username);
        foreach ($uao->minima_table as $criterion_code => $tuple) {
            // skip checking any quiz criteria for pass
            if (startswith($criterion_code, 'quiz/')) {
                $quiz_requirements[] = $criterion_code;
                continue;
            }
            // 4th entry in the tuple is a boolean for if the criteria is satisfied
            if ($tuple[3] == false) {
                $ready_ignoring_quizzes = false;
            }
        }
        if ($ready_ignoring_quizzes && $quiz_requirements) {
            echo "<div class='callout'>";
            echo "<div class='calloutheader'>";
            echo sprintf(
                _("You are just a few quiz pages away from %s!"),
                $round_target
            );
            echo "</div>";

            echo "<p>";
            echo sprintf(
                _("You are almost eligible to work in the %s round! The only thing left is to have:"),
                $round_target,
            );
            echo "</p>";
            echo "<ul>";
            foreach ($quiz_requirements as $quiz_type) {
                echo "<li>" . $ACCESS_CRITERIA[$quiz_type] . "</li>";
            }
            echo "</ul>";
            echo "<p>";
            if ($round->after_satisfying_minima == 'REQ-AUTO') {
                echo sprintf(
                    _("After you pass them you can go to the <a href='%s'>%s</a> page to be automatically granted access, find a project that interests you, and start proofreading."),
                    "$round_url$round_target",
                    $round_target
                );
            } else {
                echo sprintf(
                    _("After you pass them, view the round's <a href='%s'>entrance requirements</a> for further details."),
                    "$round_url$round_target#Entrance_Requirements",
                    $round_target
                );
            }
            echo "</p>";
            echo "<p>$please_move_up</p>";
            echo "</div>";
            return;
        }
    }
}

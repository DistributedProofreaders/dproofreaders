<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'page_tally.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'misc.inc'); // get_integer_param()

// This file deals with the gradual revelation of site features,
// based on the number of pages proofread by the user.
// (So far, it only has the code that is shared between multiple files.
// Maybe it should include unshared stuff too, for completeness.)


function get_pages_proofed_maybe_simulated()
// Retrieve the number of pages proofread by the current user.
// For demo purposes, allow the user (via the URL parameter 'numofpages')
// to pretend to have a different number of pages,
// as long as it's less than their actual number.
{
    global $pguser;

    $pagesproofed = user_get_ELR_page_tally( $pguser );
    $pagesproofed = get_integer_param($_GET, 'numofpages', $pagesproofed, 0, $pagesproofed);

    return $pagesproofed;
}

function welcome_see_beginner_forum( $pagesproofed, $page_id )
{
    global $code_url, $ELR_round, $beginners_site_forum_idx;

    if ($pagesproofed <= 100)
    {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo _("Welcome!");
        echo "</div>";

        // If the user is not on the entry level round page, direct
        // them to that page.
        if($page_id != $ELR_round->id)
        {
            echo "<p>";
            echo sprintf(_('To start proofreading, see the list of projects on the <a href="%1$s">%2$s</a> round page.'), "$code_url/{$ELR_round->relative_url}", $ELR_round->id);
            echo "</p>";
        }
        // If they are on the entry level round page, let them know that here
        // is a good place to start.
        else
        {
            echo "<p>";
            // We explicitly don't include a jump to the list of projects in
            // the text to make them scroll down pass the simple proofreading
            // guidelines.
            echo _("This page is where you can find a project to start proofreading. Choose a title from the list of projects at the bottom of this page.");
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

function thoughts_re_mentor_feedback( $pagesproofed )
{
    global $forums_url;

    if ($pagesproofed >= 15 && $pagesproofed < 200)
    {
        echo "<p>";
        echo sprintf(_("New Proofreaders: <a href='%s'>What did you think of the Mentor feedback you received?</a>"), "$forums_url/viewtopic.php?t=6651");
        echo "</p>";
    }
}

function alert_re_unread_messages( $pagesproofed )
{
    global $pguser;

    if ($pagesproofed <= 300)
    {
        $numofPMs = get_number_of_unread_messages($pguser);
        if ($numofPMs > 0)
        {
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
    if($pagesproofed < 100)
    {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo _("Looking for projects to proofread?");
        echo "</div>";

        echo "<p>" . sprintf(_("If you're looking for projects to proofread, consider using the list on the <a href='%1\$s'>%2\$s</a> round page."), "$code_url/{$ELR_round->relative_url}#{$ELR_round->id}", $ELR_round->id) . "</p>";
        echo "</p>";

        echo "<p><small>";
        echo _("After a period of time, this message will no longer appear.");
        echo "</small></p>";
        echo "</div>";
    }
}

function encourage_p1_to_p2($round)
{
    global $code_url, $pguser;

    if($round != 'P1')
        return;

    // see if the user already has access to P3 or P2, if they do just
    // encourage them to proof there instead
    $userSettings =& Settings::get_Settings($pguser);
    $p2_access = $userSettings->get_boolean("P2.access");
    $p3_access = $userSettings->get_boolean("P3.access");
    if($p2_access || $p3_access)
    {
        if($p3_access)
            $round_target = "P3";
        else
            $round_target = "P2";

        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo "Consider working on projects in $round_target!";
        echo "</div>";

        echo "<p>Please consider proofreading a project in $round_target rather than here in P1. With the large influx of new users, having eligible users working in <a href='$code_url/tools/proofers/round.php?round_id=$round_target'>$round_target</a> helps significantly.</p>";
        echo "<p>Thank you!</p>";
        echo "</div>";
        return;
    }

    // if they are already eligible but have not requested access, tell them
    $round = get_Round_for_round_id("P2");
    $uao = $round->user_access($pguser);
    if($uao->all_minima_satisfied)
    {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo "You are eligible to work in P2!";
        echo "</div>";

        echo "<p>You are eligible to work on projects in P2! Simply <a href='$code_url/tools/proofers/round.php?round_id=P2#Entrance_Requirements'>request access</a> and find a <a href='$code_url/tools/proofers/round.php?round_id=P2'>P2</a> project that interests you.</p>";
        echo "<p>Please consider working on P2 projects rather than projects here in P1. With the large influx of new users, having eligible users working in <a href='$code_url/tools/proofers/round.php?round_id=P2'>P2</a> helps significantly.</p>";
        echo "<p>Thank you!</p>";
        echo "</div>";
        return;
    }

    // they aren't yet eligible, but if they've been here for the required
    // number of days and done the required number of pages and just need to
    // take and pass the quiz, so nudge them along
    $uao = $round->user_access($pguser);
    if($uao->minima_table["P1"][3] && $uao->minima_table["days since reg"][3])
    {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo "You are just a few quizzes away from P2!";
        echo "</div>";

        echo "<p>You are almost eligible to work in the P2 round! The only thing left is to pass the short <a href='$code_url/quiz/start.php?show_level=P_MOD'>moderate proofreading quizzes</a>. After you pass them you can <a href='$code_url/tools/proofers/round.php?round_id=P2#Entrance_Requirements'>request access</a> and you'll be able to work on projects in <a href='$code_url/tools/proofers/round.php?round_id=P2'>P2</a>.</p>";
        echo "<p>Please consider working on P2 projects rather than projects here in P1. With the large influx of new users, having eligible users working in <a href='$code_url/tools/proofers/round.php?round_id=P2'>P2</a> helps significantly.</p>";
        echo "<p>Thank you!</p>";
        echo "</div>";
        return;
    }
}

// vim: sw=4 ts=4 expandtab

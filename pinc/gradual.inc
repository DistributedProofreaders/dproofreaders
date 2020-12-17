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

    // If the ELR_round requires access and the user doesn't currently
    // have it, show them what they need to do.
    $accessable_stages = get_stages_user_can_work_in(User::current_username());
    if(!isset($accessable_stages[$ELR_round->id]))
    {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo _("Welcome New Proofreader!");
        echo "</div>";

        echo "<p>";
        echo sprintf(_('Before you can begin proofreading, you must:'));
        echo "<ul>";
        $uao = $ELR_round->user_access(User::current_username());
        foreach ($uao->minima_table as $row)
        {
            list($criterion_str, $minimum, $user_score, $satisfied) = $row;
            echo "<li>$criterion_str</li>";
        }
        echo "</ul>\n";
        echo "</p>";

        echo "<p>";
        echo sprintf(_('After that you will be able to work in <a href="%1$s">%2$s</a>, the first proofreading round.'), "$code_url/{$ELR_round->relative_url}", $ELR_round->id);
        echo "</div>";
    }
    elseif ($pagesproofed <= 100)
    {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo _("Welcome New Proofreader!");
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

            echo "<p>";
            echo _("It's best to select a \"BEGINNERS ONLY\" project if one is available in a language in which you are proficient. These projects have been specially prepared to teach you our style of proofing. You can expect to receive feedback from a mentor on pages you proofread in BEGINNERS ONLY projects. This feedback will likely come at least a few days after you have completed the pages.");
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

    if ($pagesproofed < 300)
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

        echo "<p><small>";
        echo _("After a period of time, this message will no longer appear.");
        echo "</small></p>";
        echo "</div>";
    }
}

function maybe_output_new_proofer_project_message()
{
    global $code_url, $ELR_round;

    // Help direct new proofers to projects to proof.
    $pagesproofed = get_pages_proofed_maybe_simulated();
    if($pagesproofed < 100)
    {
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

// vim: sw=4 ts=4 expandtab

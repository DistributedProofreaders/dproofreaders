<?PHP
include_once($relPath.'site_vars.php');
include_once($relPath.'page_tally.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'forum_interface.inc');

// This file deals with the gradual revelation of site features,
// based on the number of pages proofed by the user.
// (So far, it only has the code that is shared between multiple files.
// Maybe it should include unshared stuff too, for completeness.)


function get_pages_proofed_maybe_simulated()
// Retrieve the number of pages proofed by the current user.
// For demo purposes, allow the user (via the URL parameter 'numofpages')
// to pretend to have a different number of pages,
// as long as it's less than their actual number.
{
    global $pguser, $userP;

    $pagesproofed = user_get_ELR_page_tally( $pguser );
    if ( TRUE )
    {
        $ru_tallyboard = new TallyBoard('R*', 'U');
        $pagesproofed += $ru_tallyboard->get_current_tally( $userP['u_id'] );
    }

    if (isset($_GET['numofpages']))
    {
        if ($_GET['numofpages'] < $pagesproofed)
        {
            $pagesproofed =  $_GET['numofpages'];
        }
    }

    return $pagesproofed;
}

function welcome_see_beginner_forum( $pagesproofed, $page_id )
{
    global $code_url, $ELR_round, $beginners_site_forum_url;

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
            echo sprintf(_("To start proofreading, see the list of projects on the <a href='%s'>%s</a> round page."), "$code_url/{$ELR_round->relative_url}", $ELR_round->id);
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
        echo sprintf(_("Please see our <a href='%s'>Beginner's Forum</a> for answers to common questions."), $beginners_site_forum_url);
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
        echo "<hr width='75%'>\n";

        echo sprintf(_("New Proofreaders: <a href='%s'>What did you think of the Mentor feedback you received?</a>"), "$forums_url/viewtopic.php?t=6651");
        echo "<br><br>";
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

// vim: sw=4 ts=4 expandtab
?>

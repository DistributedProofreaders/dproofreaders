<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'dp_main.inc');

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
    global $pguser;

    $result = mysql_query("
        SELECT pagescompleted
        FROM users
        WHERE username = '$pguser'
    ") or die(mysql_error());
    $pagesproofed = mysql_result($result,0);

    if (isset($_GET['numofpages']))
    {
        if ($_GET['numofpages'] < $pagesproofed)
        {
            $pagesproofed =  $_GET['numofpages'];
        }
    }

    return $pagesproofed;
}

function welcome_see_beginner_forum( $pagesproofed )
{
    global $theme, $beginners_site_forum_url;

    if ($pagesproofed <= 100)
    {
        echo "<hr width='75%'>\n";

        echo '<font face="'. $theme['font_mainbody'] .'" color = "blue" size=+3><br><b>';
        echo _("Welcome");
        echo "</b></font><br><br>";
        echo _("Please see our");
        echo " <a href='$beginners_site_forum_url'>";
        echo _("Beginner's Forum");
        echo "</a> ";
        echo _("for answers to common questions.");
        echo "<br><br>\n";
    }
}

function thoughts_re_mentor_feedback( $pagesproofed )
{
    global $forums_url;

    if ($pagesproofed >= 15 && $pagesproofed < 200)
    {
        echo "<hr width='75%'>\n";

        echo _("New Proofreaders:");
        echo " <a href='$forums_url/viewtopic.php?t=6651'>";
        echo _("What did you think of the Mentor feedback you received?");
        echo "</a><br><br>";
    }
}

// vim: sw=4 ts=4 expandtab
?>

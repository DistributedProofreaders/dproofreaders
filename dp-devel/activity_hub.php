<?PHP
// This page covers all project-related activities of the site.
// For each, it:
// -- describes the activity;
// -- briefly summarizes its current state; and
// -- gives a link to the particular page for that activity.
//
// (Leaves out non-project-related activities like:
// forums, documentation/faqs, development, admin.)

$relPath="./pinc/";
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'gradual.inc');


if ($userP['i_newwin']==1) { include($relPath.'js_newwin.inc'); }

theme(_("Activity Hub"), "header");

echo "<center><h1>Activity Hub</h1></center>\n";


$pagesproofed = get_pages_proofed_maybe_simulated();


// Unread messages
if ($pagesproofed <= 300)
{
    $result = mysql_query("SELECT user_id FROM phpbb_users WHERE username='".$GLOBALS['pguser']."' LIMIT 1");
    $pguser_id = mysql_result($result, 0, "user_id");

    $result = mysql_query("SELECT COUNT(*) as num FROM phpbb_privmsgs WHERE privmsgs_to_userid = $pguser_id && privmsgs_type = 1 || privmsgs_to_userid = $pguser_id && privmsgs_type = 5");
    $numofPMs = (int) mysql_result($result, 0, "num");
    if ($numofPMs > 0)
    {
        echo "<hr width='75%'>\n";

        echo "<br><br><font color='red' size=3><b>";
        echo _("You have received a private message in your Inbox!");
        echo "</b></font><br><br><font color='red'>";
        echo _("This could be from somebody sending you feedback on some of the pages you had proofread earlier. We strongly recommend you READ your messages. Near the upper right corner of this page, there is a link that says Inbox. Just click on that to open your Inbox.");
        echo "</font><br><br><i><font size=-1>";
        echo _("(After a while this explanatory paragraph will not appear when you have new messages, but the link to your Inbox will always be up there and when you have new messages that will be shown in the link)");
        echo "</font></i><br><br>\n";
    }

}


welcome_see_beginner_forum( $pagesproofed );


// Site News
if ($pagesproofed >= 20)
{
    echo "<hr width='75%'>\n";

    if ($pagesproofed < 40)
    {
        echo "<font size=-1 face=" . $theme['font_mainbody'] . "><i>";
        echo _("Now that you have proofread 20 pages you can see the Site News. This is updated regularly with announcements from the administrators.");
        echo "<br>";
        echo _("(This explanatory line will eventually vanish.)");
        echo "</i></font><br><br>\n";
    }

    $result = mysql_query("SELECT date_posted, message FROM news ORDER BY uid DESC LIMIT 1");
    $news = mysql_fetch_assoc($result);
    echo "<font size=2 face=" . $theme['font_mainbody'] . "><center><b>";
    echo _("News Update for")." ".strftime(_("%A, %B %e, %Y"), $news['date_posted'])." (<a href='$code_url/pastnews.php'>";

    echo _("archives") . "</a>)";


    // this commented out until fuller rollout

    // echo " <a href='$code_url/feeds/backend.php?content=news'><img src='$code_url/graphics/xml.gif'></a>";
    // echo "<a href='$code_url/feeds/backend.php?content=news&type=rss'><img src='$code_url/graphics/rss.gif'></a>";

    echo "</b></font><br><br><font size=2 face=";


    echo $theme['font_mainbody'] . ">".$news['message']."</center></font>\n";

    if ( user_is_a_sitemanager() )
    {
        echo "<hr width='30%'>\n";
        echo "<center>";
        echo "Site Admin: <a href='$code_url/tools/site_admin/sitenews.php'>" . _("Update Site News") . "</a>";
        echo "</center>";
    }

    echo "<br>\n";

    include("./stats/currentstatestats.php");
}

thoughts_re_mentor_feedback( $pagesproofed );

// =============================================================================

echo "\n<hr>\n";

if ( user_is_PM() )
{
    echo "You are a Project Manager, so you get this link:\n";
    echo "<br>\n";
    echo "<a href='$code_url/tools/project_manager/projectmgr.php'>" . _("Manage Projects") . "</a>";
    echo "<br>\n";
}

// ----------------------------------

$res = mysql_query("
    SELECT state, COUNT(*)
    FROM projects
    GROUP BY state
") or die(mysql_error());

$n_projects_in_state_ = array();
while ( list($project_state,$count) = mysql_fetch_row($res) )
{
    $n_projects_in_state_[$project_state] = $count;
}

function summarize_projects( $project_states )
{
    global $n_projects_in_state_;

    $total = 0;
    foreach ($project_states as $project_state)
    {
        $count = $n_projects_in_state_[$project_state];
        $total += $count;
    }
    echo sprintf( _('There are %d projects in this stage:'), $total );

    echo "<ul>\n";
    foreach ($project_states as $project_state)
    {
        $count = array_get( $n_projects_in_state_, $project_state, 0 );
        $label = project_states_text($project_state);
        echo "<li>$count $label</li>\n";

    }
    echo "</ul>\n";
}

echo "<ul>\n";

// Providing Content
{
    echo "<li>\n";
    echo _("Providing Content");
    echo "</b></font><br>";
    echo _("Want to help out the site by providing material for us to proofread? ");
    echo "<a href='$code_url/faq/scan/submitting.php'>";
    echo _("Find out how!");
    echo "</a>\n";
    echo "</li>\n";
    echo "<br>\n";
}

if ($site_supports_metadata)
{
    // Clearance Approvals
    if (user_is_a_sitemanager())
    {
        echo "<li>\n";
        echo "<a href='$code_url/tools/site_admin/proj_approvals.php'>" . _("Clearance Approvals") . "</a>";
        echo "</li>\n";
        echo "<br>\n";
    }

    // Metadata Collection
    {
        echo "<li>\n";
        echo "<a href='$code_url/tools/proofers/md_available.php'>" . _("Metadata Collection") . "</a>";
        echo "</li>\n";
        echo "<br>\n";
    }
}

// Page-Editing Rounds
for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
{
    $prd = get_PRD_for_round($rn);
    echo "<li>\n";

    echo "({$prd->round_id}) <a href='$code_url/tools/proofers/round.php?round_id={$prd->round_id}'>{$prd->round_name}</a>";
    echo "<br>\n";

    echo $prd->description;
    echo "<br>\n";

    if ( $prd->minimum_user_pages > 0 )
    {
        if ( $pagesproofed >= $prd->minimum_user_pages )
        {
            echo _("You have proofed enough pages to work in this round.");
        }
        else
        {
            echo sprintf(
                _("You must proof %d more pages to work in this round."),
                $prd->minimum_user_pages - $pagesproofed );
        }
        echo "<br>\n";
    }

    summarize_projects( array(
        $prd->project_waiting_state,
        $prd->project_available_state,
        // $prd->project_complete_state,
        // $prd->project_unavailable_state,
        // $prd->project_bad_state
        )
    );

    echo "</li>\n";
    echo "<br>\n";
}

// Post-Processing
{
    echo "<li>\n";

    echo "(PP) <a href='$code_url/tools/post_proofers/post_proofers.php'>Post-Processing</a>";
    echo "<br>\n";

    echo _("After going through two rounds of proofreading, the books need to be massaged into a final e-text.");
    echo "<br>\n";

    $minimum_user_pages = 400;
    if ( $minimum_user_pages > 0 )
    {
        if ( $pagesproofed >= $minimum_user_pages )
        {
            echo _("You have proofed enough pages to work in this stage.");
        }
        else
        {
            echo sprintf(
                _("You must proof %d more pages to work in this stage."),
                $minimum_user_pages - $pagesproofed );
        }
        echo "<br>\n";
    }

    summarize_projects( array(
        // PROJ_POST_FIRST_UNAVAILABLE,
        PROJ_POST_FIRST_AVAILABLE,
        PROJ_POST_FIRST_CHECKED_OUT
        )
    );

    {
        $result = mysql_query("
            SELECT COUNT(*)
            FROM projects
            WHERE checkedoutby='$pguser' && state='".PROJ_POST_FIRST_CHECKED_OUT."'
        ");
        $n_projects_checked_out_to_user = mysql_result($result,0);
        if ($n_projects_checked_out_to_user  > 0)
        {
            echo sprintf(
                _("You currently have %d projects checked out in this stage."),
                $n_projects_checked_out_to_user );
            echo "<br>\n";
        }
    }

    echo "</li>\n";
    echo "<br>\n";
}

// Post-Processing Verification
{
    echo "<li>\n";

    echo "(PPV) <a href='$code_url/tools/post_proofers/post_proofers.php'>Post-Processing Verification</a>";
    echo "<br>\n";

    echo "Once a PPer has submitted a final e-text, it needs to be checked by a PPVer before it is posted to PG.";
    echo "<br>\n";

    echo "PPVers are promoted from the ranks of PPers by peer review.";
    echo " [Is this user a PPVer?]";
    echo "<br>\n";

    summarize_projects( array(
        PROJ_POST_SECOND_AVAILABLE,
        PROJ_POST_SECOND_CHECKED_OUT,
        // PROJ_POST_COMPLETE
        )
    );

    {
        $result = mysql_query("
            SELECT COUNT(*)
            FROM projects
            WHERE checkedoutby='$pguser' && state='".PROJ_POST_SECOND_CHECKED_OUT."'
        ");
        $n_projects_checked_out_to_user = mysql_result($result,0);
        if ($n_projects_checked_out_to_user  > 0)
        {
            echo sprintf(
                _("You currently have %d projects checked out in this stage."),
                $n_projects_checked_out_to_user );
            echo "<br>\n";
        }
    }

    echo "</li>\n";
    echo "<br>\n";
}

echo "</ul>\n";


theme("", "footer");

// vim: sw=4 ts=4 expandtab
?>

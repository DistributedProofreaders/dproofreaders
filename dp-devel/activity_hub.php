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
include_once($relPath.'site_news.inc');



if ($userP['i_newwin']==1) { include($relPath.'js_newwin.inc'); }

theme(_("Activity Hub"), "header");

echo "<center><h1>Activity Hub</h1></center>\n";

echo "<p>\n";
echo _("Welcome to the DP Activity Hub. From this page you can view the phases of DP production. The information below is customised for you, and details the phases of production in which you can particpate. Follow the links to the specific areas of the site.");
echo "</p>\n";


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
        
        echo "<center><hr width='75%'></center>\n";

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
    echo "<center><hr width='75%'>\n";

    if ($pagesproofed < 40)
    {
        echo "<font size=-1 face=" . $theme['font_mainbody'] . "><i>";
        echo _("Now that you have proofread 20 pages you can see the Site News. This is updated regularly with announcements from the administrators.");
        echo "<br>";
        echo _("(This explanatory line will eventually vanish.)");
        echo "</i></font><br><br>\n";
    }

    show_site_news_for_page("activity_hub.php");
    random_news_item_for_page("activity_hub.php");
    include("./stats/currentstatestats.php");
    echo "<center>\n";
}

thoughts_re_mentor_feedback( $pagesproofed );

// =============================================================================

echo "\n<hr>\n";

if ( user_is_PM() )
{
    echo "<br>\n";
    echo "<a href='$code_url/tools/project_manager/projectmgr.php'>" . _("Manage My Projects") . "</a>";
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

// -----------

function summarize_projects( $project_states, $filtertype_stem )
{
    global $n_projects_in_state_;
    global $pguser;
    global $theme;

    $total = 0;
    foreach ($project_states as $project_state)
    {
        $count = array_get( $n_projects_in_state_, $project_state, 0 );
        $total += $count;
    }

    $font_str = "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='-1'>";

    echo "<table border='3' CELLSPACING='1' CELLPADDING='5'>";
    echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td align='center' rowspan='2'>".$font_str.
         _("All projects")."</font></td>";
    $title_row = '';
    foreach ($project_states as $project_state)
    {
        $state_label_name = project_states_text($project_state);
        if (strpos($state_label_name, ':') > 0) {
            $state_label_name = substr($state_label_name,strpos($state_label_name, ':') + 1);
        }
        $title_row .= "<td align='center'>".$font_str.$state_label_name."</font></td>";
    }

    $title_row .= "<td align='center'>".$font_str._("Total projects")."</font></td></tr>";

    echo $title_row;

    echo "<tr>";

    foreach ($project_states as $project_state)
    {
        $count = array_get( $n_projects_in_state_, $project_state, 0 );
        echo "<td align='center'>$count</td>";
    }

    echo "<td align='center'>$total</td></tr>";

    // -----------------------

    // And now, with filtering...

    $res1 = mysql_query("
        SELECT value
        FROM user_filters
        WHERE username='$pguser' AND filtertype='{$filtertype_stem}_internal'
    ") or die(mysql_error());
    if ( mysql_num_rows($res1) == 0 )
    {
        // echo _("You have no project filtering set up for this stage.");
        echo "</table>";
        return;
    }

    list($project_filter) = mysql_fetch_row($res1);

    $states_list = '';
    foreach ( $project_states as $project_state )
    {
        if ($states_list) $states_list .= ',';
        $states_list .= "'$project_state'";
    }

    $res2 = mysql_query("
        SELECT state, COUNT(*)
        FROM projects
        WHERE state IN ($states_list) $project_filter
        GROUP BY state
    ") or die(mysql_error());

    $n_filtered_projects_in_state_ = array();
    $filtered_total = 0;
    while ( list($project_state,$count) = mysql_fetch_row($res2) )
    {
        $n_filtered_projects_in_state_[$project_state] = $count;
        $filtered_total += $count;
    }

    echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td align='center' rowspan='2'>".
         $font_str._("After applying<br>your filter")."</font></td>";
    echo $title_row;

    foreach ($project_states as $project_state)
    {
        $count = array_get( $n_filtered_projects_in_state_, $project_state, 0 );
        echo "<td align='center'>$count</td>\n";
    }
    echo "<td align='center'>$filtered_total</td>\n";
    echo "</tr>";
    echo "</table>";

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

	list($how_access, $can_access,$explanation) = $prd->user_access( $pguser, $pagesproofed );
    echo $how_access;
    echo "<br>\n";
    echo $explanation;
    echo "<br>\n";

    summarize_projects( array(
        $prd->project_waiting_state,
        $prd->project_available_state,
        // $prd->project_complete_state,
        // $prd->project_unavailable_state,
        // $prd->project_bad_state
        ),
        $prd->round_id
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
        ),
        'avail_PP'
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


// Smooth Reading
{
    echo "<li>\n";

    echo "(SR) <a href='$code_url/tools/post_proofers/smooth_reading.php'>Smooth Reading Pool</a>";
    echo "<br>\n";

    echo _("Before a PPer has submitted a final e-text, they can optionally make it available for Smooth Reading. Anyone can volunteer to Smooth Read a text, which is basically just reading through it and marking possible errors before returning it to the Post Processor.");
    echo "<br>\n";

    echo _("Every DP user can participate in this stage.");
    echo "<br><br>\n";



}


// Post-Processing Verification
{
    echo "<li>\n";

    echo "(PPV) <a href='$code_url/tools/post_proofers/post_proofers.php'>Post-Processing Verification</a>";
    echo "<br>\n";

    echo _("Once a PPer has submitted a final e-text, it needs to be checked by a PPVer before it is posted to PG.");
    echo "<br>\n";

    echo _("PPVers are promoted from the ranks of PPers by peer review.");
    echo "<br>\n";
    if ( user_is_post_proof_verifier() )
    {
        echo _("You are allowed to participate in this stage.");
    }
    else
    {
        echo _("You are not allowed to participate in this stage.");
    }
    echo "<br>\n";

    summarize_projects( array(
        PROJ_POST_SECOND_AVAILABLE,
        PROJ_POST_SECOND_CHECKED_OUT,
        // PROJ_POST_COMPLETE
        ),
        'avail_PPV'
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

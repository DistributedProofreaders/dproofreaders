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
include_once($relPath.'stages.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'gradual.inc');
include_once($relPath.'site_news.inc');


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
    echo "</center>\n";
}

thoughts_re_mentor_feedback( $pagesproofed );

// =============================================================================

echo "\n<hr>\n";

echo "<ul>\n";

if ( user_is_PM() )
{
    echo "<br>\n";
    echo "<li><a href='$code_url/tools/project_manager/projectmgr.php'>" . _("Manage My Projects") . "</a></li>";
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

function show_entrance_requirements( $minima_table, $sentences )
{
    if ( $minima_table )
    {
        echo _('Entrance Requirements') . ":\n";
        echo "<table border='1'>\n";

        echo "<tr>";
        echo "<th>" . _('Criterion') . "</th>";
        echo "<th>" . _('Minimum')  . "</th>";
        echo "<th>" . _('You')      . "</th>";
        echo "</tr>\n";

        foreach ( $minima_table as $row )
        {
            list($criterion_str, $minimum, $user_value, $satisfied) = $row;
            $bgcolor = ( $satisfied ? '#ccffcc' : '#ffcccc' );
            echo "<tr>";
            echo "<td>$criterion_str</td>";
            echo "<td>$minimum</td>";
            echo "<td bgcolor='$bgcolor'>$user_value</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    }
    foreach ( $sentences as $sentence )
    {
        echo "$sentence\n";
    }
    echo "<br>\n";
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

foreach ( $Stage_for_id_ as $stage )
{
    echo "<li>\n";

    echo "({$stage->id}) <a href='$code_url/{$stage->relative_url}'>{$stage->name}</a>";
    echo "<br>\n";

    echo $stage->description;
    echo "<br>\n";

    echo "<br>\n";

	list($can_access, $minima_table, $sentences) = $stage->user_access( $pguser, $pagesproofed );
    show_entrance_requirements( $minima_table, $sentences );
    echo "<br>\n";

    if ( is_a( $stage, 'Round' ) )
    {
        summarize_projects( array(
            $stage->project_waiting_state,
            $stage->project_available_state,
            // $stage->project_complete_state,
            // $stage->project_unavailable_state,
            // $stage->project_bad_state
            ),
            $stage->id
        );
    }
    elseif ( is_a($stage, 'Pool' ) )
    {
        summarize_projects( array(
            // $stage->project_unavailable_state,
            $stage->project_available_state,
            $stage->project_checkedout_state,
            ),
            "{$stage->id}_av"
        );

        $res = mysql_query("
            SELECT COUNT(*)
            FROM projects
            WHERE checkedoutby='$pguser' && state='{$stage->project_checkedout_state}'
        ");
        $n_projects_checked_out_to_user = mysql_result($res,0);
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

<?php
// This page covers all project-related activities of the site.
// For each, it:
// -- describes the activity;
// -- briefly summarizes its current state; and
// -- gives a link to the particular page for that activity.
//
// (Leaves out non-project-related activities like:
// forums, documentation/faqs, development, admin.)

$relPath="./pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'gradual.inc');
include_once($relPath.'site_news.inc');
include_once($relPath.'mentorbanner.inc');
include_once($relPath.'filter_project_list.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'faq.inc');

require_login();

// Load user settings
$userSettings =& Settings::get_Settings($pguser);

$title = _("Activity Hub");

output_header($title);

echo "<h1>$title</h1>";

echo get_page_header_image("HUB");

echo "<p>\n";
echo sprintf(_('Welcome to the %1$s Activity Hub. From this page you can view the phases of %1$s production.'),$site_abbreviation);
echo "\n";
echo _("Follow the links to the specific areas of the site.");
echo "</p>\n";


$pagesproofed = get_pages_proofed_maybe_simulated();

alert_re_unread_messages( $pagesproofed );

welcome_see_beginner_forum( $pagesproofed, "HUB" );

thoughts_re_mentor_feedback( $pagesproofed );


// Site News
show_news_for_page("HUB");

// Show any mentor banners.
foreach ( $Round_for_round_id_ as $round )
{
    if ( $round->is_a_mentor_round() &&
        user_can_work_on_beginner_pages_in_round($round) )
    {
        mentor_banner($round);
    }
}

// =============================================================================

// Get the project transitions for the number of projects completed today
// set the timestamp representing the start of today
$t_start_of_today = mktime(0,0,0,date('m'),date('d'),date('y'));

// For transition events (event_type = 'transition'), details2 gives
// the project's new state.
$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT details2, count(distinct projectid)
    FROM project_events
    WHERE event_type = 'transition' AND timestamp >= $t_start_of_today
    GROUP BY details2
") or die(DPDatabase::log_error());

$n_projects_transitioned_to_state_ = array();
while ( list($project_state,$count) = mysqli_fetch_row($res) )
{
    $n_projects_transitioned_to_state_[$project_state] = $count;
}

// Get the current count for the number of projects in their current state
$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT state, COUNT(*)
    FROM projects
    GROUP BY state
") or die(DPDatabase::log_error());

$n_projects_in_state_ = array();
while ( list($project_state,$count) = mysqli_fetch_row($res) )
{
    $n_projects_in_state_[$project_state] = $count;
}

// Users can elect to show project stats for all projects, or just for
// projects that match their project filter (ie: the filter used on
// the corresponding Round or Pool pages to limit the projects shown on those
// pages). We use the show_filtered_numbers userSetting value to persist the
// user's selection between visits to this page.
//
// When the user toggles to view the other version of the stats (by clicking
// the "All" or "Filtered" link) this re-requests the page with a setting for
// the show_filtered parameter. This new setting is stored in the
// show_filtered_numbers userSetting for use the next time they visit the
// page.
$user_filtered_projects_setting = $userSettings->get_boolean("show_filtered_numbers");
$page_filtered_projects_setting = (array_get( $_GET, "show_filtered", $user_filtered_projects_setting) == 1);
if($user_filtered_projects_setting != $page_filtered_projects_setting)
    $userSettings->set_boolean("show_filtered_numbers", $page_filtered_projects_setting);
$show_filtered_projects = $page_filtered_projects_setting;

$show_filtering_links = TRUE;

// Proofreaders with fewer than 21 pages can't see the filter box on the Round
// pages so prevent those users from selecting the filtered option.
if ($pagesproofed <= 20)
{
    $show_filtered_projects = FALSE;
    $show_filtering_links = FALSE;
}


progress_snapshot_table($show_filtered_projects, $show_filtering_links, ($pagesproofed < 300));

activity_descriptions();

// ----------------------------------

function progress_snapshot_table($show_filtered_projects, $show_filtering_links, $show_beginner_help)
// Prints out a table containing a row for each stage including the user's
// access for that stage as well as project and page metrics.
// Arguments:
//   $show_filtered_projects - if TRUE, the table will show numbers based on
//                             the user's project filter for that stage
//   $show_filtering_links   - if TRUE, the table will allow the user to
//                             toggle between viewing numbers for ALL projects
//                             and just those for filters.
//   $show_beginner_help     - if TRUE, the table will be prefaced with an
//                             informational paragraph
{
    global $Stage_for_id_;

    // start the table
    echo "<h2 id='progress_snapshot'>" . _("Site Progress Snapshot") . "</h2>";

    echo "<p>" . sprintf(_("The following table provides an overview of what has been happening in the various stages of e-book production since midnight server-time. Current server-time is %s."),strftime("%H:%M")) . "</p>";

    if($show_beginner_help)
    {
        echo "<p>" . _("The left side of the table lists each production stage an e-book will go through, and indicates your ability to work in that stage. The \"Projects\" section, in the center of the table, shows the total number of projects in each stage, how many of these are waiting to be made available for work, how many are currently active and available for volunteers to work on, and finally, the number of projects that have completed that stage today.") . "</p>";
        echo "<p>" . _("Each stage has a daily goal which has been designed to motivate volunteers and keep work flowing through the site. The \"Pages Today\" section, on the right side of the table, shows the number of pages we'd like to see that stage complete today, how many pages have actually been completed since midnight server-time, and a percentage representation of the progress. Further, a \"traffic light\" color-coding system indicates the likelihood of that stage reaching its goal based on the rate of pages completed so far today.") . "</p>";
        echo "<p>" . sprintf(_("See the <a href='%s'>workflow diagram</a> for more information about the overall process."), get_faq_url("DPflow.php")) . "</p>";
    }

    echo "<table class='snapshottable'>";

    // Loop through the stages three times, once each for Round, Pool, and
    // everything else.

    // Round headers
    echo "\n<tr>";
    $img_alt = attr_safe(_("Proofreading/Formatting Activities"));
    echo "<th rowspan='2' class='activity-icon-header'><img src='graphics/icon_proofer.png' alt='$img_alt' title='$img_alt'></th>";
    echo "<th colspan='4'>" .  _("Projects") . " - ";
    if($show_filtered_projects)
    {
        if($show_filtering_links)
            echo "<a href='?show_filtered=0#progress_snapshot'>" . pgettext("all projects", "All") . "</a> | ";
        echo "<b>" . _("Filtered") . "</b>";
    }
    else
    {
        echo "<b>" . pgettext("all projects", "All") . "</b>";
        if($show_filtering_links)
            echo " | <a href='?show_filtered=1#progress_snapshot'>" . _("Filtered") . "</a>";
    }
    echo "</th>";
    echo "<th colspan='3'>" . _("Pages Today") . "</th>";
    echo "</tr>\n";

    echo "<tr>";
    echo "<th>" . _("Total") . "</th>";
    echo "<th>" . _("Waiting") . "</th>";
    echo "<th>" . _("Available") . "</th>";
    echo "<th>" . _("Completed<br>Today") . "</th>";
    echo "<th class='pages-goal'>" . _("Goal") . "</th>";
    echo "<th class='pages-completed'>" . _("Completed") . "</th>";
    echo "<th>" . _("Progress") . "</th>";
    echo "</tr>\n";

    // Round rows
    foreach ( $Stage_for_id_ as $stage )
    {
        if( !is_a( $stage, 'Round' ) )
            continue;

        $desired_states = array($stage->project_waiting_state, $stage->project_available_state, $stage->project_complete_state);

        summarize_stage($stage, $desired_states, $show_filtered_projects, $stage->id);
    }

    // Pool and Stage headers
    echo "\n<tr>";
    $img_alt = attr_safe(_("Post-Processing Activities"));
    echo "<th rowspan='2' class='activity-icon-header'><img src='graphics/icon_pp.png' alt='$img_alt' title='$img_alt'></th>";
    echo "<th colspan='3'>" . _("Projects") . " - ";
    if($show_filtered_projects)
    {
        if($show_filtering_links)
            echo "<a href='?show_filtered=0#progress_snapshot'>" . pgettext("all projects", "All") . "</a> | ";
        echo "<b>" . _("Filtered") . "</b>";
    }
    else
    {
        echo "<b>" . pgettext("all projects", "All") . "</b>";
        if($show_filtering_links)
            echo " | <a href='?show_filtered=1#progress_snapshot'>" . _("Filtered") . "</a>";
    }
    echo "</th>";
    echo "<td colspan='4' class='nocell'></td>";
    echo "</tr>\n";

    echo "<tr>";
    echo "<th>" . _("Total") . "</th>";
    echo "<th>" . _("Available") . "</th>";
    echo "<th>" . _("In Progress") . "</th>";
    echo "<td colspan='4' class='nocell'></td>";
    echo "</tr>\n";

    // Pool rows
    foreach ( $Stage_for_id_ as $stage )
    {
        if( !is_a( $stage, 'Pool' ) )
            continue;

        $desired_states = array($stage->project_available_state, $stage->project_checkedout_state);

        summarize_stage($stage, $desired_states, $show_filtered_projects, "{$stage->id}_av");
    }

    // Stage rows
    foreach ( $Stage_for_id_ as $stage )
    {
        if( is_a( $stage, 'Pool' ) || is_a( $stage, 'Round') )
            continue;

        $desired_states = array();

        summarize_stage($stage, $desired_states);
    }
    echo "\n</table>\n";
    echo "<a href='faq/site_progress_snapshot_legend.php' target='_blank'>" . _("Information about this table") . "</a>";
}

function summarize_stage($stage, $desired_states, $show_filtered_projects=FALSE, $filter_type="")
// Prints out an activity summary table row for a specific stage (be it a
// Round, Pool, or Stage).
{
    global $pguser, $n_projects_in_state_, $n_projects_transitioned_to_state_;

    // Get the stage description for displaying in the title of the link.
    $description = attr_safe(strip_tags($stage->description));

    // Determine access eligibility for this stage.
    $uao = $stage->user_access( $pguser );
    if($uao->can_access)
    {
        $access_class = "access-yes";
        $access_icon = "✓";
        $access_text = _("You can work in this activity");
        $access_link = '';
    }
    elseif($uao->all_minima_satisfied)
    {
        $access_class = "access-eligible";
        $access_icon = "ⓘ";
        $access_text = _("You are eligible to work in this activity");
        $access_link = "{$stage->relative_url}#Entrance_Requirements";
    }
    else
    {
        $access_class = "access-no";
        $access_icon = "✗";
        $access_text = _("You are not yet eligible to work in this activity");
        $access_link = "{$stage->relative_url}#Entrance_Requirements";
    }

    // If we're a round, get page information and calculate status.
    if ( is_a( $stage, 'Round' ) )
    {
        $round_stats = get_site_page_tally_summary($stage->id);

        list($progress_bar_width, $progress_bar_class, $percent_complete) =
            calculate_progress_bar_properties($round_stats->curr_day_actual, $round_stats->curr_day_goal);
    }

    // Calculate the total number of projects.
    $total_projects = 0;
    $stage_totals = array();
    foreach($desired_states as $stage_state)
    {
        // Pull the number of completed projects from the project
        // transitions array and the others from the current state array.
        // Only sum the stats that aren't project_complete_state as they're
        // already included in the following round's numbers.
        // (Use '@' to suppress "Undefined property" notice:
        // not every stage has a 'project_complete_state'.)
        if($stage_state == @$stage->project_complete_state)
            $count = array_get( $n_projects_transitioned_to_state_, $stage_state, 0 );
        else
        {
            $count = array_get( $n_projects_in_state_, $stage_state, 0 );
            $total_projects += $count;
        }

        $stage_totals[$stage_state] = $count;
    }

    // Pull the project filter
    $n_projects_in_state_by_filter_ = array();
    if($show_filtered_projects)
        $project_filter = get_project_filter_sql($pguser, $filter_type);

    // We can't load filtered numbers without a filter and without
    // a list of desired states.
    $load_filtered_projects = FALSE;
    if($show_filtered_projects && $project_filter!="" && count($desired_states)!=0)
        $load_filtered_projects = TRUE;

    // Load any projects based on filters
    if($load_filtered_projects)
    {
        $states_list = '';
        foreach ( $desired_states as $desired_state )
        {
            if ($states_list) $states_list .= ',';
            $states_list .= "'$desired_state'";
            $n_projects_in_state_by_filter_[$desired_state] = 0;
            // (Use '@' to suppress "Undefined property" notice:
            // not every stage has a 'project_complete_state'.)
            if($desired_state == @$stage->project_complete_state)
                $n_projects_in_state_by_filter_[$desired_state] = _("N/A");
        }

        $res = mysqli_query(DPDatabase::get_connection(), "
            SELECT state, COUNT(*)
            FROM projects
            WHERE state IN ($states_list) $project_filter
            GROUP BY state
        ") or die(DPDatabase::log_error());

        $total_projects = 0;
        while ( list($project_state,$count) = mysqli_fetch_row($res) )
        {
            $n_projects_in_state_by_filter_[$project_state] = $count;
            $total_projects += $count;
        }
    }

    // Output the table row.
    echo "<tr>";

    // If we're showing the filter for this line, we need the
    // round cell to span two rows.
    if($show_filtered_projects)
        $span_rows = "rowspan='2'";
    else
        $span_rows = "";

    // Every row gets a label, name, and access information.
    echo "<td class='stage-column' $span_rows>";

    // Output the access status icon. If the user does not yet have access
    // make the image a link to the access requirements.
    echo "<div class='stage-access $access_class' title='" . attr_safe($access_text) . "'>";
    if($access_link)
        echo "<a href='$access_link'>";
    echo $access_icon;
    if($access_link)
        echo "</a>";
    echo "</div>";
    echo "<b>$stage->id</b><span class='stage-name'>: <a href='{$stage->relative_url}' title='$description'>{$stage->name}</a></span>";

    echo "</td>";

    // Rounds and Pools also get project totals.
    if ( is_a( $stage, 'Round' ) || is_a( $stage, 'Pool' ) )
    {
        echo "<td>$total_projects</td>";
        foreach ( $desired_states as $desired_state )
        {
            echo "<td>";
            if($load_filtered_projects)
                echo $n_projects_in_state_by_filter_[$desired_state];
            else
                echo $stage_totals[$desired_state];
            echo "</td>";
        }
    }
    else
    {
        echo "<td colspan='3' class='nocell'></td>";
    }

    // Rounds also get page totals.
    if ( is_a( $stage, 'Round' ) )
    {
        echo "<td class='pages-goal'>{$round_stats->curr_day_goal}</td>";
        echo "<td class='pages-completed'>{$round_stats->curr_day_actual}</td>";
        echo "<td><div class='progressbar $progress_bar_class' style='width: $progress_bar_width%;'>&nbsp;</div><p style='clear: both; margin: 0;'>$percent_complete%</p></td>";
    }
    else
    {
        // IE6 & 7 do not display the top and left borders of a cell if
        // border-collapse=collapse and the border=1px. The following
        // tweak is to ensure that if we show the filter that the filter
        // cell has a border all the way around it.
        if($show_filtered_projects)
            echo "<td colspan='4' class='nocell' style='border-bottom: 1px solid black;'></td>";
        else
            echo "<td colspan='4' class='nocell'></td>";
    }

    echo "</tr>\n";

    if($show_filtered_projects)
    {
        $filter_link = "{$stage->relative_url}#filter_form";
        if($load_filtered_projects)
        {
            $display_filter = get_project_filter_display($pguser, $filter_type);
            $display_filter = sprintf(_('<a href="%1$s">Filter</a>: %2$s'), $filter_link, $display_filter);
        }
        else
        {
            $display_filter = sprintf(_('<a href="%1$s">Add filter</a>'), $filter_link);
        }
        echo "<tr>";
        echo "<td colspan='7' style='text-align: left;'>";
        echo "<small>$display_filter</small>";
        echo "</td>";
        echo "</tr>";
    }
}


function activity_descriptions()
// Prints out a list of activities (Stages, Rounds, and Pools) and their
// description.
{
    global $Stage_for_id_, $code_url;

    echo "<h2>" . _("Activity descriptions") . "</h2>";
    echo "<div id='stagedescriptions'>";
    echo "<dl>\n";

    // Providing Content
    {
        echo "<dt>";
        echo _("Providing Content");
        echo "</dt>";
        echo "<dd>";
        echo sprintf(_("Want to help out the site by providing material for us to proofread? <a href='%s'>Find out how!</a>"), "$code_url/faq/cp.php");
        echo "</dd>\n";
    }

    foreach ( $Stage_for_id_ as $stage )
    {
        echo "<dt><b>$stage->id</b>: <a href='$code_url/{$stage->relative_url}'>{$stage->name}</a></dt>";
        echo "<dd>{$stage->description}</dd>\n";
    }

    echo "</dl>";
    echo "</div>";
}


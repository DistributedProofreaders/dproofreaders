<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'page_table.inc');

require_login();

$projectid = get_projectID_param($_GET, 'project');
$show_image_size = get_integer_param($_GET, 'show_image_size', 0, 0, 1);
$round_for_page_selection = get_enumerated_param($_GET, 'select_by_round', null, array_keys($Round_for_round_id_), true);

// select_by_user can have three possible values:
//    NULL = show all users
//    '' = show current user
//    <username> = show specific user
$username_for_page_selection = array_get($_GET, 'select_by_user', null);
if ($username_for_page_selection === '') {
    $username_for_page_selection = $pguser;
}

// Validate user selection
if ($username_for_page_selection) {
    // Only SAs and PFs can use select_by_user. This is to prevent users from
    // tweaking this value and determining what user proofread what page in a
    // project. This is possible because while the table renderer will hide
    // usernames for users not privileged to access them, select_by_user and
    // select_by_round will still only present the requested rows allowing the
    // requester to determine what pages a user did in which round.
    if (! (user_is_a_sitemanager() || user_is_proj_facilitator())) {
        $username_for_page_selection = $pguser;
    }

    // Validate the requested username
    if (!User::is_valid_user($username_for_page_selection)) {
        $username_for_page_selection = null;
    }
}

// if $username_for_page_selection is NULL, unset $round_for_page_selection
if (!$username_for_page_selection) {
    $round_for_page_selection = null;
}

$project = new Project($projectid);
$state = $project->state;
$title = $project->nameofwork;
$page_details_str = _('Page Detail');

output_header("$page_details_str: $title", NO_STATSBAR);

echo "<h1>" . html_safe($title) . "</h1>\n";
echo "<h2>$page_details_str</h2>\n";

echo "<p>" . return_to_project_page_link($projectid, ["expected_state=$state"]) . "</p>\n";

if ($project->check_pages_table_exists($warn_message)) {
    echo_detail_legend();

    echo "<p>" . _("It is <b>strongly</b> recommended that you view page differentials by right-clicking on a diff link and opening the link in a new window or tab.") . "</p>";

    echo "<p>";
    if (!is_null($username_for_page_selection)) {
        if (is_null($round_for_page_selection)) {
            echo sprintf(
                _("Showing only the pages of user '%s'."),
                html_safe($username_for_page_selection)
            );
        } else {
            echo sprintf(
                _("Showing only the pages of user '%1\$s' in round %2\$s."),
                html_safe($username_for_page_selection),
                html_safe($round_for_page_selection)
            );
        }
        $blurb = _("Show all pages instead.");
        echo "&nbsp;&nbsp;";
        echo "<a href='?project=$projectid&show_image_size=$show_image_size'>$blurb</a>";
    } else {
        $blurb = _("Show just my pages.");
        echo "<a href='?project=$projectid&show_image_size=$show_image_size&select_by_user'>$blurb</a>";
    }
    echo "</p>";

    echo_page_table($project, $show_image_size, false, $username_for_page_selection, $round_for_page_selection);
} else {
    echo "<p class='warning'>$warn_message</p>\n";
}

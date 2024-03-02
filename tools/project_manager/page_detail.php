<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'page_table.inc');

require_login();

$errors = [];

$projectid = get_projectID_param($_GET, 'project');
$show_image_size = get_integer_param($_GET, 'show_image_size', 0, 0, 1);
$round_for_page_selection = get_round_param($_GET, 'select_by_round', null, true);

// select_by_user can have three possible values:
//    NULL = show all users
//    '' = show current user
//    <username> = show specific user
$username_for_page_selection = array_get($_GET, 'select_by_user', null);
if ($username_for_page_selection === '') {
    $username_for_page_selection = User::current_username();
}

$project = new Project($projectid);

// Only the project PM (and SA/PFs) can use select_by_user. This is to prevent
// users from using this value to determine what user proofread what page in a
// project. This is possible because while the table renderer will hide
// usernames for users not privileged to access them, select_by_user and
// select_by_round will still only present the requested rows allowing the
// requester to determine what pages a user did in which round.
$may_select_user = $project->can_be_managed_by_current_user;

// Validate user selection
if ($username_for_page_selection) {
    if (!$may_select_user) {
        $username_for_page_selection = User::current_username();
    }

    // Validate the requested username
    if (!User::is_valid_user($username_for_page_selection)) {
        $errors[] = sprintf(
            _('Error: "%1$s" is not a valid user. Showing all pages.'),
            $username_for_page_selection
        );
        $username_for_page_selection = null;
    }
}

// if $username_for_page_selection is NULL, unset $round_for_page_selection
if (!$username_for_page_selection) {
    $round_for_page_selection = null;
}

$state = $project->state;
$title = $project->nameofwork;
$page_details_str = _('Page Detail');

output_header("$page_details_str: $title", NO_STATSBAR);

// NB We use a flexbox to position the linkbox div rather than just using
// `float: right` like the other linkboxes in the codebase do because on
// this page the inflexible and very wide page details table causes the
// linkbox to display in ugly locations.
echo "<div style='display: flex; justify-content: space-between'>";
echo "<div>";
echo "<h1>" . html_safe($title) . "</h1>\n";
echo "<h2>$page_details_str</h2>\n";
echo "</div>";
if ($may_select_user) {
    echo_filter_box($projectid, $show_image_size, $username_for_page_selection, $round_for_page_selection);
}
echo "</div>";

foreach ($errors as $error) {
    echo "<p class='error'>" . html_safe($error) . "</p>";
}
echo "<p>" . return_to_project_page_link($projectid, ["expected_state=$state"]) . "</p>\n";


function echo_filter_box(string $projectid, int $show_image_size, ?string $username, ?Round $round)
{
    $username = $username === '' ? User::current_username() : $username;
    $roundid = $round ? $round->id : '';
    // auto margins and `flexbox: justify-content` interact in surprising ways.
    // Override #linkbox's `margin-right: auto` so that the containing flexbox
    // div can position the linkbox at the right of the div.
    echo "<div id='linkbox' style='margin-right: 0'>";
    echo "<form action='#' method='get'>";
    echo "<p>";
    echo "<input type='hidden' name='project' value='$projectid'>";
    echo "<input type='hidden' name='show_image_size' value='$show_image_size'>";
    echo "<table>";
    echo "<tr><td colspan=2>", _("Filter pages by:"), "</td></tr>";
    echo "<tr>";
    echo "<td class='bold'><label for='sbu'>", _("Username"), "</label></td>";
    echo "<td><input type='text' id='sbu' name='select_by_user' value='" . attr_safe($username) . "' required></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td class='bold'><label for='sbr'>", _("User in round"), "</label></td>";
    echo "<td><select id='sbr' name='select_by_round'>";
    $rounds = array_map(fn ($r) => [$r->id, $r->id], Rounds::get_all());
    array_unshift($rounds, [_('Any Round'), '']);
    foreach ($rounds as [$label, $value]) {
        $selected = $value == $roundid ? " selected" : "";
        echo "<option value='$value'$selected>$label</option>\n";
    }
    echo "</select></tr>";
    echo "<tr>";
    echo "<td colspan=2>";
    echo "<input type='submit' value='" . attr_safe(_("Refresh")) . "'>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    echo "</form>";
    echo "</p>";
    echo "</div>";
}

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
                html_safe($round_for_page_selection->id)
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

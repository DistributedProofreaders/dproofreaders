<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'prefs_options.inc');  // for PRIVACY_* constants
include_once($relPath.'theme.inc');          // for page marginalia
include_once($relPath.'TallyBoard.inc');     // for TallyBoard
include_once($relPath.'Project.inc');
include_once($relPath.'mentoring.inc');
include_once($relPath.'LPage.inc');

require_login();

// Display page header.
$title = _("For Mentors");
output_header($title);

echo "<h1>$title</h1>";

// ---------------------------------------------------------------

// Decide which mentoring-round we're dealing with.

$mentoring_round = get_round_param($_GET, 'round_id', null, true);
if (!$mentoring_round) {
    // Consider the page they came from.
    $referer = $_SERVER['HTTP_REFERER'] ?? "";

    // If they're coming to this page from a MENTORS ONLY book in X2,
    // referrer should contain &expected_state=X2.proj_avail.
    foreach (Rounds::get_all() as $round) {
        if (strpos($referer, $round->project_available_state)) {
            $mentoring_round = $round;
            break;
        }
    }

    if (!isset($mentoring_round)) {
        $mentoring_rounds = get_mentoring_rounds();
        if ($mentoring_rounds) {
            $mentoring_round = $mentoring_rounds[0];
        } else {
            die("There are no mentoring rounds!");
        }
    }
}

if (!$mentoring_round->is_a_mentor_round()) {
    die("$mentoring_round->id is not a mentoring round!");
}

// ---------------------------------------------------------------

// Are there other mentoring rounds? If so, provide mentoring links for them.
$other_mentoring_rounds = [];
foreach (Rounds::get_all() as $round) {
    if ($round->is_a_mentor_round() && $round->id != $mentoring_round->id) {
        $other_mentoring_rounds[] = $round;
    }
}
if (count($other_mentoring_rounds) > 0) {
    echo "<p>" . _('Show this page for:');

    $links = [];
    foreach ($other_mentoring_rounds as $other_round) {
        $url = "$code_url/tools/proofers/for_mentors.php?round_id={$other_round->id}";
        $links[] = "<a href='$url'>{$other_round->id}</a>";
    }
    echo implode(" | ", $links);
    echo "</p>";
}

// ---------------------------------------------------------------

if (!user_can_work_on_beginner_pages_in_round($mentoring_round)) {
    echo "<p class='warning'>";
    echo sprintf(
        _("You do not have access to 'Mentors Only' projects in %s."),
        $mentoring_round->id
    );
    echo "</p>\n";
    exit;
}

// ---------------------------------------------------------------

$mentored_round = $mentoring_round->mentee_round;

// output a table of contents with links to anchors on this page
echo "<p>";
echo sprintf(_("Projects with pages available to Mentors in round %s."), "<b>$mentoring_round->id</b>");
echo " ";
echo sprintf(_("Please check the Saved column in the 'Which proofreader did each page...' section for each project listed below, to make sure you first work on the project with the oldest pages saved in %s."), "<b>$mentored_round->id</b>");
echo "</p>";

$projects_available = get_beginner_projects_in_state($mentoring_round->project_available_state, $mentored_round);
if ($projects_available) {
    echo "<ol>";
    foreach ($projects_available as $proj_obj) {
        echo "<li><a href='#$proj_obj->projectid'>";
        echo output_project_label($proj_obj->nameofwork, $proj_obj->authorsname, $proj_obj->t_left_mentee_round);
        echo "</a></li>";
    }
    echo "</ol>";
} else {
    echo "<p><i>" . _("No projects available") . "</i></p>";
}

// output a listing of projects in this mentoring round that are in a waiting state
echo "<p>";
echo sprintf(_("Projects for Mentors, waiting to be released into round %s."), "<b>$mentoring_round->id</b>");
echo " ";
echo _("Oldest project listed first.");
echo "</p>";

$projects_waiting = get_beginner_projects_in_state($mentoring_round->project_waiting_state, $mentored_round);
if ($projects_waiting) {
    echo "<ol>";
    foreach ($projects_waiting as $proj_obj) {
        $project = new Project($proj_obj->projectid);
        echo "<li>";
        echo output_project_label($proj_obj->nameofwork, $proj_obj->authorsname, $proj_obj->t_left_mentee_round);
        if (in_array($mentoring_round->project_waiting_state, $project->get_hold_states())) {
            // TRANSLATORS: string indicates that the project is "on hold"
            echo sprintf(" <b>[%s]</b>", _("On hold"));
        }
        echo "</li>";
    }
    echo "</ol>";
} else {
    echo "<p><i>" . _("No projects waiting") . "</i></p>";
}

// output details about each available project
foreach ($projects_available as $proj_obj) {
    output_project_details($mentoring_round, $mentored_round, $proj_obj->projectid, $proj_obj->nameofwork, $proj_obj->authorsname);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function output_project_label(string $nameofwork, string $authorsname, ?int $date = null)
{
    // TRANSLATORS: format is <title> by <author>.
    echo sprintf(_("%1\$s by %2\$s"), $nameofwork, $authorsname);
    if (!is_null($date)) {
        $date = date("Y-m-d H:i T", $date);
        echo " ($date)";
    }
}

/**
 * For each mentorable project (in this round), show a summary (one line per mentee)
 * and then a listing (one line per page).
 */
function output_project_details(Round $mentoring_round, Round $mentored_round, string $projectid, string $nameofwork, string $authorsname)
{
    global $code_url;

    echo "<hr>";

    // Display project summary info
    $proj_url = "$code_url/project.php?id=$projectid";
    echo "<p id='$projectid' class='bold'>";
    output_project_label("<a href='$proj_url'>" . html_safe($nameofwork) . "</a>", html_safe($authorsname));
    echo "</p>" ;

    checkout_return_project_pages($mentoring_round, $mentored_round, $projectid);

    output_page_summary_table($mentored_round, $projectid);

    echo "<p>" ;
    echo _('Which proofreader did each page...') ;
    echo "</p>";

    output_page_list_table($mentored_round, $projectid);
}

/**
 * Checkout/return pages for a project.
 *
 * We do this per-project instead of at the top so that messages about the
 * checkout are included with the project on the page.
 */
function checkout_return_project_pages(Round $mentoring_round, Round $mentored_round, string $projectid)
{
    // return if the user hasn't requested checkouts/returns for this project
    if ((!isset($_POST["checkout_proofreader"]) && !isset($_POST["return_proofreader"]))
        || get_projectID_param($_POST, "projectid") != $projectid) {
        return;
    }

    // get the list of pages to checkout/return for the given user
    $checkout_pages = [];
    $return_pages = [];
    if (isset($_POST["checkout_proofreader"])) {
        // pages for proofreader
        validate_projectID($projectid);
        $sql = sprintf(
            "
            SELECT image
            FROM $projectid
            WHERE $mentored_round->user_column_name = '%s'
                AND state = '%s'
            ",
            DPDatabase::escape($_POST["proofreader"]),
            DPDatabase::escape($mentoring_round->page_avail_state)
        );
        $res = DPDatabase::query($sql);
        while ([$image] = mysqli_fetch_row($res)) {
            $checkout_pages[] = $image;
        }
    } elseif (isset($_POST["return_proofreader"])) {
        // pages for proofreader
        validate_projectID($projectid);
        $sql = sprintf(
            "
            SELECT image
            FROM $projectid
            WHERE $mentored_round->user_column_name = '%s'
                AND state = '%s'
                AND {$mentored_round->mentor_round->user_column_name} = '%s'
            ",
            DPDatabase::escape($_POST["proofreader"]),
            DPDatabase::escape($mentoring_round->page_out_state),
            DPDatabase::escape(User::current_username())
        );
        $res = DPDatabase::query($sql);
        while ([$image] = mysqli_fetch_row($res)) {
            $return_pages[] = $image;
        }
    }

    // try to check out / return each one for the current user
    // LPage enforces that the user has the ability to checkout or return the
    // page, including that the page is in a valid state for that action
    $project = new Project($projectid);
    $pages_checked_out = 0;
    $pages_returned = 0;
    $errors = [];
    foreach ($checkout_pages as $image) {
        try {
            $lpage = get_indicated_LPage($project->projectid, $project->state, $image, $mentoring_round->page_avail_state, 0);
            $lpage->checkout(User::current_username());
            $pages_checked_out += 1;
        } catch (Exception $exception) {
            $errors[] = "$image: " . $exception->getMessage();
        }
    }
    foreach ($return_pages as $image) {
        try {
            $lpage = get_indicated_LPage($project->projectid, $project->state, $image, $mentoring_round->page_out_state, 0);
            $lpage->returnToRound(User::current_username());
            $pages_returned += 1;
        } catch (Exception $exception) {
            $errors[] = "$image: " . $exception->getMessage();
        }
    }

    if ($pages_checked_out) {
        echo "<p>" . sprintf(_("Successfully checked out %d page(s) in this project."), $pages_checked_out) . "</p>";
    }
    if ($pages_returned) {
        echo "<p>" . sprintf(_("Successfully returned %d page(s) in this project."), $pages_returned) . "</p>";
    }
    if ($errors) {
        echo "<p class='error'>" . _("Encountered errors checking out/returning the requested pages") . ":</p>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . html_safe($error) . "</li>";
        }
        echo "</ul>";
    }
}

// -------------------------------------------------------------------

function get_beginner_projects_in_state(string $state, Round $mentored_round): array
{
    $mentored_round_detail = $mentored_round->project_complete_state;

    $sql = sprintf(
        "
        SELECT
            projects.projectid,
            projects.nameofwork,
            projects.authorsname,
            MAX(project_events.timestamp) AS t_left_mentee_round
        FROM projects LEFT OUTER JOIN project_events USING (projectid)
        WHERE
            difficulty = 'BEGINNER'
            AND project_events.event_type = 'transition'
            AND project_events.details2 = '%s'
            AND projects.state = '%s'
        GROUP BY projects.projectid
        ORDER BY
            project_events.timestamp ASC
        ",
        DPDatabase::escape($mentored_round_detail),
        DPDatabase::escape($state)
    );
    $result = DPDatabase::query($sql);
    $projects = [];
    while ($proj_obj = mysqli_fetch_object($result)) {
        $projects[] = $proj_obj;
    }
    return $projects;
}

// -------------------------------------------------------------------

function output_page_summary_table(Round $mentored_round, string $projectid)
{
    global $code_url;

    $round_tallyboard = new TallyBoard($mentored_round->id, 'U');

    [$joined_with_user_page_tallies, $user_page_tally_column] =
            $round_tallyboard->get_sql_joinery_for_current_tallies('u.u_id');

    echo "<table class='striped basic'>";
    echo "<tr>";
    echo "<th>" . _("Proofreader") . "</th>";
    echo "<th>" . _("Pages this project") . "</th>";
    echo "<th>" . _("Pages available") . "</th>";
    echo "<th>" . _("Checkout") . "</th>";
    echo "<th>" . _("Return") . "</th>";
    // TRANSLATORS: %s is a round ID
    echo "<th>" . sprintf(_("Total %s Pages"), $mentored_round->id) . "</th>";
    echo "<th>" . _("Joined") . "</th>";
    echo "</tr>";

    validate_projectID($projectid);
    $sql = sprintf(
        "
        SELECT
            u.username AS username,
            u.u_id as u_id,
            COUNT(1) AS pages_this_project,
            $user_page_tally_column AS total_pages,
            u.date_created AS joined,
            (
                SELECT count(*)
                FROM $projectid
                WHERE $mentored_round->user_column_name = u.username
                    AND state like '%%avail'
            ) AS pages_avail,
            (
                SELECT count(*)
                FROM $projectid
                WHERE $mentored_round->user_column_name = u.username
                    AND state like '%%out'
                    AND {$mentored_round->mentor_round->user_column_name} = '%s'
            ) AS pages_out
        FROM $projectid AS p
            INNER JOIN users AS u ON p.{$mentored_round->user_column_name} = u.username
            $joined_with_user_page_tallies
        GROUP BY p.{$mentored_round->user_column_name}
        ",
        DPDatabase::escape(User::current_username())
    );
    $res = DPDatabase::query($sql);
    while ($row = mysqli_fetch_assoc($res)) {
        echo "<tr>";
        echo "<td>" . sprintf("<a href='{$code_url}/stats/members/mdetail.php?&id=%d'>%s</a>", $row["u_id"], $row["username"]) . "</td>";
        echo "<td>" . $row["pages_this_project"] . "</td>";
        echo "<td>" . $row["pages_avail"] . "</td>";
        echo "<td>";
        if ($row["username"] != User::current_username() && $row["pages_avail"]) {
            echo "<form method='POST' action='#$projectid'>";
            echo "<input type='hidden' name='projectid' value='$projectid'>";
            echo "<input type='hidden' name='proofreader' value='" . attr_safe($row["username"]) . "'>";
            echo "<input type='submit' name='checkout_proofreader' value='" . attr_safe(_("Check out available pages")) . "'>";
            echo "</form>";
        }
        echo "</td>";
        echo "<td>";
        if ($row["username"] != User::current_username() && $row["pages_out"]) {
            echo "<form method='POST' action='#$projectid'>";
            echo "<input type='hidden' name='projectid' value='$projectid'>";
            echo "<input type='hidden' name='proofreader' value='" . attr_safe($row["username"]) . "'>";
            echo "<input type='submit' name='return_proofreader' value='" . attr_safe(_("Return out pages")) . "'>";
            echo "</form>";
        }
        echo "</td>";
        echo "<td>" . $row["total_pages"] . "</td>";
        echo "<td>" . icu_date("yyyy MMM dd HH:mm", $row["joined"]) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// -------------------------------------------------------------------

function output_page_list_table(Round $mentored_round, string $projectid)
{
    echo "<input type='hidden' name='projectid' value='$projectid'>";
    echo "<table class='striped basic'>";
    echo "<tr>";
    echo "<th>" . _("Page") . "</th>";
    echo "<th>" . _("Saved") . "</th>";
    echo "<th>" . _("Proofreader") . "</th>";
    echo "<th>" . _("WordCheck Events") . "</th>";
    echo "<th>" . _("Current Page State") . "</th>";
    echo "<th>" . _("Current Proofreader") . "</th>";
    echo "</tr>";

    validate_projectID($projectid);

    // copied from pinc/LPage.inc:
    $order = "
        (
            SELECT MIN({$mentored_round->time_column_name})
            FROM $projectid
            WHERE {$mentored_round->user_column_name}
              = p.{$mentored_round->user_column_name}
        ),
        {$mentored_round->user_column_name},
        image
    ";

    validate_projectID($projectid);
    $sql = "
        SELECT
            p.image AS page,
            p.{$mentored_round->time_column_name} AS saved_date,
            p.{$mentored_round->user_column_name} AS username,
            p.{$mentored_round->mentor_round->user_column_name} AS current_username,
            p.state,
            (
                SELECT count(*)
                FROM wordcheck_events
                WHERE projectid = '$projectid'
                    AND round_id = '$mentored_round->id'
                    AND username = p.{$mentored_round->user_column_name}
                    AND image = p.image
            ) AS wc_events
        FROM $projectid AS p
        ORDER BY $order
    ";
    $res = DPDatabase::query($sql);
    while ($row = mysqli_fetch_assoc($res)) {
        echo "<tr>";
        echo "<td>" . $row["page"] . "</td>";
        echo "<td>" . icu_date("yyyy MMM dd HH:mm", $row["saved_date"]) . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["wc_events"] . "</td>";
        echo "<td>" . $row["state"] . "</td>";
        echo "<td>" . $row["current_username"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

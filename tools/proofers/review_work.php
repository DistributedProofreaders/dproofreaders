<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'Project.inc'); // does_project_page_table_exist()
include_once($relPath.'User.inc');

require_login();

define("MESSAGE_INFO", 0);
define("MESSAGE_WARNING", 1);
define("MESSAGE_ERROR", 2);

// get an array of round IDs
$rounds = Rounds::get_all();

// load any data passed into the page
$username = array_get($_GET, "username", $pguser);
$work_round = get_round_param($_GET, "work_round_id", null, true);
$review_round = get_round_param($_GET, "review_round_id", null, true);
$sampleLimit = get_integer_param($_GET, "sample_limit", 0, 0, null);
$days = get_integer_param($_GET, "days", 100, 0, null);
$use_eval_query = get_integer_param($_GET, "use_eval_query", 1, 0, 1);

$user_can_review_others = user_is_a_sitemanager() ||
      user_is_an_access_request_reviewer() ||
      user_is_proj_facilitator();

// if the user isn't a site manager or an access request reviewer,
// or a project facilitator they can only access their own pages
if (!$user_can_review_others) {
    $username = $pguser;
    $use_eval_query = 0;
}

// start the page
$title = _('Review Work');

output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>\n";

if ($user_can_review_others) {
    echo "<p>" . _("This tool allows you to review your work or the work of another user.") . "</p>";
} else {
    echo "<p>" . _("This tool allows you to review your work in a round with changes made in later rounds.") . "</p>";
}

// show form
echo "<form action='review_work.php' method='GET'>";
echo "<table class='basic'>";
if ($user_can_review_others) {
    // only let site admins or reviewers to access non-self records and eval query
    echo  "<tr>";
    echo   "<th>" . _("Username") . "</th>";
    echo   "<td><input name='username' type='text' size='26' value='" . attr_safe($username) . "' required></td>";
    echo  "</tr>";
    echo  "<tr>";
    echo   "<th>" . _("Use Evaluation Query") . "</th>";
    echo   "<td><select name='use_eval_query'>";
    _echo_eval_query_select($use_eval_query);
    echo   "</select></td>";
    echo  "</tr>";
}
echo  "<tr>";
echo   "<th>" . _("Work Round") . "</th>";
echo   "<td><select name='work_round_id'>";
_echo_round_select($rounds, $work_round);
echo    "</select>";
echo  "</tr>";
echo  "<tr>";
echo   "<th>" . _("Review Round") . "</th>";
echo   "<td><select name='review_round_id'>";
_echo_round_select(array_slice($rounds, 1), $review_round);
echo     "</select>";
echo  "</tr>";
echo  "<tr>";
echo   "<th>" . _("Max days since last save") . "</th>";
echo   "<td><input name='days' type='number' min='0' value='$days' required></td>";
echo  "</tr>";
echo  "<tr>";
echo   "<th>" . _("Max diffs to show") . "</th>";
echo   "<td><input name='sample_limit' type='number' min='0' value='$sampleLimit' required></td>";
echo  "</tr>";
echo "</table>";
echo "<input type='submit' value='Search'>";
echo "</form>";

function _echo_eval_query_select($selected)
{
    $options = [1 => _("Yes"), 0 => _("No")];
    foreach ($options as $value => $name) {
        echo "<option value='$value'";
        if ($value == $selected) {
            echo " selected";
        }
        echo ">$name</option>";
    }
}

function _echo_round_select($rounds, $selected_round)
{
    foreach ($rounds as $round) {
        echo "<option value='" . attr_safe($round->id) . "'";
        if ($selected_round && $round == $selected_round->id) {
            echo " selected";
        }
        echo ">" . $round->id . "</option>";
    }
}

// if not all the required values are set (ie: not passed into the script)
// stop the page here
if (empty($username) ||
   $work_round == null ||
   $review_round == null) {
    exit;
}

if (!User::is_valid_user($username)) {
    echo "<p class='error'>" . _("Invalid username") . "</p>";
    exit;
}

// confirm the review_round is later than work_round
if ($review_round->round_number <= $work_round->round_number) {
    echo "<p class='error'>" . _("Review Round should be a round later than Work Round.") . "</p>";
    exit;
}


echo "<hr>";

// we should have valid information at this point
$time_limit = time() - $days * 24 * 60 * 60;

// Queries against the page_events table perform poorly and put a lot of load
// against the database. Queries against user_project_info table are pretty
// fast. Ideally we'd use only the query against user_project_info but that
// sorts the listing by the last time the user worked on the project in ANY
// round, not just the work round. This is problematic for eval reviewers.
// Here we split the middle and allow evaluators (and PMs and PFs) to use
// the slower evaluation query and route everyone else to the faster and more
// performant one.
if ($use_eval_query) {
    $sql = sprintf(
        "
        SELECT
            page_events.projectid,
            state,
            nameofwork,
            deletion_reason,
            FROM_UNIXTIME(MAX(timestamp)) AS time_of_latest_save
        FROM page_events LEFT OUTER JOIN projects USING (projectid)
        WHERE round_id = '%s' AND
              page_events.username = '%s' AND
              event_type = 'saveAsDone' AND
              timestamp > %d
        GROUP BY page_events.projectid
        ORDER BY time_of_latest_save DESC
        ",
        DPDatabase::escape($work_round->id),
        DPDatabase::escape($username),
        $time_limit
    );
} else {
    $sql = sprintf(
        "
        SELECT
            user_project_info.projectid,
            state,
            nameofwork,
            deletion_reason,
            FROM_UNIXTIME(t_latest_page_event) AS time_of_latest_save
        FROM user_project_info LEFT OUTER JOIN projects USING (projectid)
        WHERE user_project_info.username = '%s' AND
              t_latest_page_event > %d
        GROUP BY user_project_info.projectid
        ORDER BY t_latest_page_event DESC
        ",
        DPDatabase::escape($username),
        $time_limit
    );
}
$res2 = DPDatabase::query($sql);

// This next message used to start with $num_projects, but that was incorrect.
// There are two codepaths below that result in the project not showing up in
// either listing.
echo "<h2>";
echo sprintf(_('Projects with pages saved in <b>%1$s</b> by <b>%2$s</b> within the last <b>%3$d</b> days'), $work_round->id, $username, $days);
echo "</h2>";

// ---------------------------------------------
// snippets for use in queries
$state_builder = [];
for ($rn = $review_round->round_number + 1; $rn <= Rounds::get_last()->round_number; $rn++) {
    $round = get_Round_for_round_number($rn);
    // this will be used in sprintf()s below so for the final query to have a
    // single % in it, we need to make it 4x here
    $state_builder[] = sprintf(" OR state LIKE '%s.%%%%'", DPDatabase::escape($round->id));
}

$has_been_saved_in_review_round = "(" .
    sprintf("state='%s'", DPDatabase::escape($review_round->page_save_state)) .
    implode(" ", $state_builder) .
    ")";

$there_is_a_diff = "
    $work_round->text_column_name
    !=
    BINARY $review_round->text_column_name
";

// ---------------------------------------------

echo "<p>";
echo sprintf(_("These projects are (or have been) available in <b>%s</b>."), $review_round->id);
echo "</p>\n";

echo "<table class='basic striped'>";

echo "<tr>";
echo "<th>" . _("Title") . "</th>";
echo "<th>" . _("Current State") . "</th>";
if ($use_eval_query) {
    echo "<th>" . sprintf(_("Last saved by user in %s"), $work_round->id) . "</th>";
} else {
    echo "<th>" . _("Last saved by user in project") . "</th>";
}
// TRANSLATORS: %s is the round ID.
echo "<th>" . sprintf(_("Pages saved by user in %s"), $work_round->id) . "</th>";
// TRANSLATORS: %s is the round ID.
echo "<th>" . sprintf(_("Pages saved by others in %s"), $review_round->id) . "</th>";
echo "<th>" . _("Pages with differences") . "</th>";
if ($sampleLimit > 0) {
    // TRANSLATORS: %s is a number of diffs.
    echo "<th>" . sprintf(_("%s most recent diffs"), $sampleLimit) . "</th>";
}
echo "</tr>";

$total_n_saved = 0;
$total_n_latered = 0;
$total_n_w_diff = 0;
$total_valid_projects = 0;

$messages = [];  // will contain error messages
// each message will be an array of: project, state, other info, level

$projects_done = []; // the projects that we've done rows for

// go through the list of projects with pages saved in the work round, according
// to the page_events table
while ([$projectid, $state, $nameofwork, $deletion_reason, $time_of_latest_save] = mysqli_fetch_row($res2)) {
    $url = "$code_url/tools/project_manager/page_detail.php?project=$projectid&amp;select_by_user=$username&amp;select_by_round=" . $work_round->id;

    // if the project has been deleted, find out whether it was merged into another one
    // and if so, operate on the one it was merged into
    // this may give us duplicates in the list. This relies on the deletion
    // reason containing 'merged' and the projectid.

    $was_merged = ($state == PROJ_DELETE) &&
        str_contains($deletion_reason, 'merged');
    // see if the deletion reason contains "merged", and if so look for the projectid
    if ($was_merged &&
        (1 == preg_match('/\b(projectID[0-9a-f]{13})\b/', $deletion_reason, $matches))) {
        $deleted_projectid = $projectid;
        $projectid = $matches[0];
        $url = "$code_url/tools/project_manager/page_detail.php?project=$projectid&amp;select_by_user=$username&amp;select_by_round=" . $work_round->id;
        $deleted_state = $state;
        $deleted_nameofwork = $nameofwork;
        $deleted_url = "$code_url/tools/project_manager/project.php?id=$deleted_projectid";
        $project = new Project($projectid);
        $state = $project->state;
        $nameofwork = $project->nameofwork;
        // OK, the information is now all for the project that the deleted one was merged into
        $messages[] = [
            "<a href='$deleted_url'>" . html_safe($deleted_nameofwork) . "</a>",
            $deleted_state,
            sprintf(_("Merged into %s"), "<a href='$url'>" . html_safe($nameofwork) . "</a>"),
            MESSAGE_INFO,
        ];
    }
    // TODO: what do we do if it was merged but we haven't found a projectid?

    // see if we've already done this project
    if ("" != @$projects_done[$projectid]) {
        // we've done it before
        continue; // go on to next project
    }

    // haven't done it yet
    $projects_done[$projectid] = $projectid;

    // see if the pages table exists
    if (!does_project_page_table_exist($projectid)) {
        // table doesn't exist. We are not interested.
        $messages[] = [
            "<a href='$url'>" . html_safe($nameofwork) . "</a>",
            $state,
            _("Page information no longer available"),
            MESSAGE_ERROR,
        ];
        continue;
    }


    // See if the user worked on any pages in this round
    validate_projectID($projectid);
    $sql = sprintf(
        "
        SELECT COUNT(*)
        FROM {$projectid}
        WHERE {$work_round->user_column_name} = '%s' AND
              {$work_round->time_column_name} > %d
        ",
        DPDatabase::escape($username),
        $time_limit
    );
    $work_pages_done_result = DPDatabase::query($sql);
    [$pages_worked_in_review_round] = mysqli_fetch_row($work_pages_done_result);
    mysqli_free_result($work_pages_done_result);
    // if not, skip this project
    if ($pages_worked_in_review_round == 0) {
        continue;
    }

    // see if it finished the work round
    $sql = sprintf(
        "
        SELECT MAX(timestamp)
        FROM project_events
        WHERE projectid = '%s' AND
              details2 = '%s'
        ",
        DPDatabase::escape($projectid),
        DPDatabase::escape($work_round->project_complete_state)
    );
    $work_round_result = DPDatabase::query($sql);
    [$max_done_timestamp] = mysqli_fetch_row($work_round_result);
    mysqli_free_result($work_round_result);
    if (null == $max_done_timestamp) {
        // hasn't finished the work round. We are not interested.
        $messages[] = [
            "<a href='$url'>" . html_safe($nameofwork) . "</a>",
            $state,
            sprintf(_("Has not finished %s"), $work_round->id),
            MESSAGE_INFO,
        ];
        continue;
    }

    // see if it actually went through the review round.
    $sql = sprintf(
        "
        SELECT COUNT(*) 
        FROM project_events
        WHERE projectid = '%s' AND
              details2 ='%s' AND
              timestamp >= %d
        ",
        DPDatabase::escape($projectid),
        DPDatabase::escape($review_round->project_available_state),
        $max_done_timestamp
    );
    $review_round_result = DPDatabase::query($sql);
    [$done_in_rround] = mysqli_fetch_row($review_round_result);
    mysqli_free_result($review_round_result);
    if (0 == $done_in_rround) {
        // hasn't been proofread in review round. We are not interested.
        $messages[] = [
            "<a href='$url'>" . html_safe($nameofwork) . "</a>",
            $state,
            sprintf(_('Has not been proofread in %1$s (%2$d pages worked on)'), $review_round->id, $pages_worked_in_review_round),
            MESSAGE_INFO,
        ];
        continue;
    }

    // OK, we are definitely interested in this project
    $total_valid_projects++;

    validate_projectID($projectid);
    $query = sprintf("
        SELECT
            COUNT(*),
            IFNULL( SUM($has_been_saved_in_review_round), 0 ),
            IFNULL( SUM($has_been_saved_in_review_round AND $there_is_a_diff), 0 )
        FROM $projectid
        WHERE {$work_round->user_column_name} = '%s'
        ", DPDatabase::escape($username));
    $res3 = DPDatabase::query($query);
    [$n_saved, $n_latered, $n_with_diff] = mysqli_fetch_row($res3);
    mysqli_free_result($res3);
    if ($n_latered > 0) {
        $n_with_diff_percent = sprintf("%d", ($n_with_diff / $n_latered) * 100);
    } else {
        $n_with_diff_percent = 0;
    }
    $table_found = 1;

    // don't include this project if none of the user's pages have been proofread in the
    // review round
    if ($n_saved == 0) {
        // Don't print a message in the Other Projects table to avoid confusion -- this will
        // need to be re-introduced once we limit the projects to ones that the user has
        // actually worked on in the work round
        // $messages[] = [
        //     "<a href='$url'>" . html_safe($nameofwork) . "</a>",
        //     $state,
        //     sprintf(_("Has not been proofread in %s"), $review_round->id),
        //     MESSAGE_INFO,
        // ];
        continue;
    }

    // now get the $sampleLimit most recent pages that are different
    // don't use page_events because of the problems with merged projects
    $diffLinkString = "";
    if ($sampleLimit > 0) {
        validate_projectID($projectid);
        $query = sprintf(
            "
            SELECT image 
            FROM $projectid AS proj 
            WHERE $has_been_saved_in_review_round AND 
                  $there_is_a_diff AND
                  {$work_round->user_column_name} = '%s'
            ORDER BY {$work_round->time_column_name} DESC, image 
            LIMIT %d
            ",
            DPDatabase::escape($username),
            $sampleLimit
        );
        $result = DPDatabase::query($query);
        $diffLinkString = "";
        while ([$image] = mysqli_fetch_row($result)) {
            $diffLinkString .= "<a href='../project_manager/diff.php?project=$projectid&amp;image=$image&amp;L_round=$work_round->id&amp;R_round=$review_round->id'>$image</a> ";
        }
        mysqli_free_result($result);
    }

    // not sure why the pages that have been saved in the review round
    // are highlighted as that data doesn't really tell me much
    // ... but it shows the evaluator which projects to look at
    // ... but not necessary with revamp of page

    $n_w_diff_class = ($n_with_diff > 0 ? "has-diff" : "");

    $total_n_saved += $n_saved;
    $total_n_latered += $n_latered;
    $total_n_w_diff += $n_with_diff;

    echo "<tr>";
    echo "<td><a href='$url'>" . html_safe($nameofwork) . "</a></td>";
    echo "<td nowrap>";
    echo get_medium_label_for_project_state($state);
    echo "</td>";
    echo "<td>$time_of_latest_save</td>";
    echo "<td class='right-align'>$n_saved</td>";
    echo "<td class='right-align'>$n_latered</td>";
    echo "<td class='right-align $n_w_diff_class'>$n_with_diff ($n_with_diff_percent%)</td>";
    if ($sampleLimit > 0) {
        echo "<td class='$n_w_diff_class'>$diffLinkString</td>";
    }
    echo "</tr>";
    echo "\n";
} // end of doing each project

if ($total_n_latered > 0) {
    $total_n_w_diff_percent = sprintf("%d", ($total_n_w_diff / $total_n_latered) * 100);
} else {
    $total_n_w_diff_percent = 0;
}

echo "<tr>";
echo "<th colspan='3'>" . _("Totals") . ":</th>";
echo "<th class='right-align'>$total_n_saved</th>";
echo "<th class='right-align'>$total_n_latered</th>";
echo "<th class='right-align'>$total_n_w_diff ($total_n_w_diff_percent%)</th>";
if ($sampleLimit > 0) {
    echo "<th></th>";
}
echo "</tr>";

echo "</table>";
echo sprintf(_("(%d projects)"), $total_valid_projects);

// show messages
$total_invalid_projects = count($messages);
if ($total_invalid_projects) {
    echo "<h2>" . _("Other projects") . "</h2>";
    echo "<table class='basic striped'>";
    echo "<tr>";
    echo    "<th>" . _("Project") . "</th>";
    echo    "<th>" . _("Current State") . "</th>";
    echo    "<th>" . _("Status") ."</th>";
    echo "</tr>";
    foreach ($messages as $message) {
        echo "<tr><td>{$message[0]}</td>";
        echo "<td nowrap>";
        echo get_medium_label_for_project_state($message[1]);
        echo "</td>";
        echo"<td>{$message[2]}</td></tr>";
    }
    echo "</table>";
    echo sprintf(_("(%d projects)"), $total_invalid_projects);
}

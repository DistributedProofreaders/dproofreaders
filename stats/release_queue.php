<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'release_queue.inc');

require_login();

$user_can_see_queue_settings = $ordinary_users_can_see_queue_settings ||
    user_is_a_sitemanager() || user_is_proj_facilitator();

$listing_view_modes = [
    "populated" => [
        "label" => _("Enabled & populated queues"),
    ],
    "enabled" => [
        "label" => _("Enabled queues"),
    ],
    "all" => [
        "label" => _("All queues"),
    ],
];

$round = get_round_param($_GET, 'round_id', null, true);
$name = @$_GET['name'];
$listing_view_mode = get_enumerated_param($_GET, "show", "populated", array_keys($listing_view_modes));
$unheld_only = get_integer_param($_GET, 'unheld_only', 0, 0, 1);

$title = _("Release Queues");
output_header($title, NO_STATSBAR);
echo "<h1>" . html_safe($title) . "</h1>\n";

if (is_null($round)) {
    _show_available_rounds();
} else {
    if (isset($name)) {
        _show_queue_details($round, $name, $unheld_only);
    } else {
        _show_round_queues($round, $listing_view_mode);
    }
}

//-----------------------------------------------------------------------------

function _show_available_rounds()
{
    global $Round_for_round_id_;

    echo html_safe(_("Each round has its own set of release queues.")), "\n";
    echo html_safe(_("Please select the round that you're interested in:")), "\n";
    echo "<ul>\n";
    foreach (array_keys($Round_for_round_id_) as $round_id) {
        echo "<li><a href='?round_id=$round_id'>$round_id</a></li>\n";
    }
    echo "</ul>\n";
}

function _show_round_queues($round, $listing_view_mode)
{
    global $code_url, $listing_view_modes, $user_can_see_queue_settings;

    echo "<p>";
    echo "<a href='?'>" . html_safe(_("Back to queue round selection")) ."</a> | ";
    echo "<a href='$code_url/tools/proofers/round.php?round_id=$round->id'>" . html_safe(sprintf(_("Back to round %s"), $round->id)) . "</a>";
    echo "</p>\n";

    echo "<h2>" . html_safe(sprintf(_("Queues for %s"), $round->id)) . "</h2>";

    echo "<h3>" . html_safe("Quick statistics") . "</h3>";

    echo "<table class='themed theme_striped' style='width: auto;'>\n";
    echo "<tr>";
    echo "<th>" . html_safe(_("Round")) . "</th>";
    echo "<th>" . html_safe(_("Projects available")) . "</th>";
    echo "<th>" . html_safe(_("Pages available")) . "</th>";
    echo "</tr>";

    foreach ([$round, get_Round_for_round_number($round->round_number + 1)] as $r) {
        if (!$r) {
            continue;
        }
        [$projects_available, $pages_available] = _get_num_projects_and_pages_available($r);
        echo "<tr>";
        echo "<td>" . html_safe($r->id) . "</td>";
        echo "<td class='right-align'>$projects_available</td>";
        echo "<td class='right-align'>$pages_available</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<h3>" . html_safe("Queues") . "</h3>";

    output_tab_bar($listing_view_modes, $listing_view_mode, "show", "round_id=$round->id");
    echo "<br>";

    $errors = [];
    $columns = 5;
    echo "<table class='themed theme_striped'>\n";
    {
        echo "<tr>";
        // TRANSLATORS: "Order" refers to a number used to sort items in a specific order
        echo "<th>", html_safe(_("Order")), "</th>\n";
        if (!in_array($listing_view_mode, ["enabled", "populated"])) {
            echo "<th>", html_safe(_("Enabled")), "</th>\n";
            $columns += 1;
        }
        echo "<th>", html_safe(_("Name")), "</th>\n";
        echo "<th>", html_safe(_("Current length")), "</th>\n";
        echo "<th>", html_safe(_("Length without holds")), "</th>\n";
        if ($user_can_see_queue_settings) {
            echo "<th>", html_safe(_("Release criterion")), "</th>\n";
            $columns += 1;
        }
        echo "<th>", html_safe(_("Comment")), "</th>\n";
        echo "</tr>\n";
    }

    $q_sql = sprintf(
        "
        SELECT *
        FROM queue_defns
        WHERE round_id='%s' AND ((%s) OR (SUBSTR(name, 1, 1) = '*'))
        ORDER BY ordering
        ",
        DPDatabase::escape($round->id),
        in_array($listing_view_mode, ["enabled", "populated"]) ? "enabled = 1" : "1"
    );
    $q_res = DPDatabase::query($q_sql);

    while ($qd = mysqli_fetch_object($q_res)) {
        // Because queues are ordered, there is a convention to break them up
        // into sections with a disabled queue at the start of the section
        // indicating the section's name. These queue names start with "***"
        // and we identify them here and treat them differently.
        if (startswith($qd->name, "***")) {
            echo "<tr>";
            echo "<td colspan='$columns' class='bold'>" . html_safe(str_replace("***", "", $qd->name)) . "</td>";
            echo "</tr>";
            continue;
        }

        [$current_length, $current_unheld_length] = _get_queue_length($qd->project_selector, $round->project_waiting_state);
        if ($listing_view_mode == "populated" && $current_length == 0) {
            continue;
        }

        if ($current_length === null) {
            $current_length = '???';
            $current_unheld_length = '???';
            $holds = '???';
            $errors[] = sprintf(
                _('There is a syntax error in the project selector for #%1$d "%2$s"'),
                $qd->ordering,
                $qd->name
            );
            $link_cell = html_safe($qd->name);
        } else {
            $ename = urlencode($qd->name);
            $link_cell = "<a href='?round_id=$round->id&amp;name=$ename'>" . html_safe($qd->name) . "</a>";
            $holds = $current_length - $current_unheld_length;
        }

        echo "<tr>";
        echo "<td>$qd->ordering</td>\n";
        if (!in_array($listing_view_mode, ["enabled", "populated"])) {
            echo "<td>" . html_safe($qd->enabled ? _("Yes") : "") . "</td>";
        }
        echo "<td>$link_cell</td>\n";
        echo "<td>$current_length</td>\n";
        echo "<td>$current_unheld_length</td>\n";
        if ($user_can_see_queue_settings) {
            echo "<td>", html_safe($qd->release_criterion), "</td>\n";
        }
        echo "<td>", html_safe($qd->comment), "</td>\n";
        echo "</tr>\n";
    }
    echo "</table>\n";

    foreach ($errors as $error) {
        echo "<p class='error'>" . html_safe($error) . "</p>";
    }
}

function _get_queue_length($cooked_project_selector, $project_waiting_state)
{
    $state_clause = sprintf(
        "state='%s'",
        DPDatabase::escape($project_waiting_state)
    );
    $c_sql = "
        SELECT count(*) as total, SUM(CASE WHEN project_holds.state is NULL THEN 1 ELSE 0 END) as unheld
        FROM projects
            LEFT OUTER JOIN project_holds USING (projectid, state)
        WHERE ($cooked_project_selector)
            AND $state_clause
    ";

    try {
        $c_res = DPDatabase::query($c_sql);
        $row = mysqli_fetch_row($c_res);
        return [$row[0], $row[1] ?? 0];
    } catch (DBQueryError $error) {
        return [null, null];
    }
}

function _show_queue_details($round, $name, $unheld_only)
{
    global $code_url, $user_can_see_queue_settings;

    $sql = sprintf(
        "
        SELECT *
        FROM queue_defns
        WHERE round_id='%s' AND name='%s'
        ",
        DPDatabase::escape($round->id),
        DPDatabase::escape($name)
    );
    $qd = mysqli_fetch_object(DPDatabase::query($sql));
    if (!$qd) {
        die(html_safe("No such release queue '$name' in $round->id."));
    }

    echo "<p><a href='?round_id=$round->id'>" . html_safe(sprintf(_("Back to release queues for round %s"), $round->id)) ."</a></p>\n";

    // TRANSLATORS: %s is the name of this release queue.
    echo "<h2>" . html_safe(sprintf(_('%1$s release queue: %2$s'), $round->id, $name)) . "</h2>";

    $cooked_project_selector = cook_project_selector($qd->project_selector);

    [$length, $length_unheld] = _get_queue_length($cooked_project_selector, $round->project_waiting_state);

    $fields = [
        _("Comment") => $qd->comment,
        _("Status") => $qd->enabled ? _("enabled") : _("disabled"),
        _("Current length") => $length,
        _("Length without holds") => $length_unheld,
    ];

    if ($user_can_see_queue_settings) {
        $fields[_("Selector")] = $qd->project_selector;
        if ($cooked_project_selector != $qd->project_selector) {
            $fields[_("Filled-in")] = $cooked_project_selector;
        }
        $fields[_("Release Criterion")] = $qd->release_criterion;
    }

    echo "<p>";
    foreach ($fields as $label => $value) {
        echo "<b>" . html_safe($label) . "</b>: " . html_safe($value) . "<br>";
    }
    echo "</p>";

    echo "<h3>" . _("Recent queue statistics") . "</h3>";

    echo "<p>" . _("Number of projects and pages released from this queue recently.") . "</p>";

    echo "<table class='themed theme_striped' style='width: auto;'>\n";
    echo "<tr>";
    echo "<th>" . html_safe(_("Days ago")) . "</th>";
    echo "<th>" . html_safe(_("Projects")) . "</th>";
    echo "<th>" . html_safe(_("Pages")) . "</th>";
    echo "</tr>";

    foreach ([1, 7, 28, 84] as $days_ago) {
        $seconds_ago = time() - 60 * 60 * 24 * $days_ago;
        [$projects_released, $pages_released] = _get_num_projects_and_pages_released_in_last($name, $seconds_ago, $round->project_waiting_state);
        echo "<tr>";
        echo "<td class='right-align'>$days_ago</td>";
        echo "<td class='right-align'>$projects_released</td>";
        echo "<td class='right-align'>$pages_released</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<h3>" . html_safe(_("Projects currently in queue")) . "</h3>";

    $ename = urlencode($qd->name);
    echo "<p>";
    if ($unheld_only) {
        echo "<a href='?round_id=$round->id&amp;name=$ename&amp;unheld_only=0'>" . html_safe("Show all projects") . "</a> | ";
        echo "<b>" . html_safe(_("Showing unheld projects")) . "</b>";
    } else {
        echo "<b>" . html_safe(_("Showing all projects")) . "</b> | ";
        echo "<a href='?round_id=$round->id&amp;name=$ename&amp;unheld_only=1'>" . html_safe("Show unheld projects") . "</a>";
    }
    echo "</p>";


    $comments_url1 = DPDatabase::escape("<a href='$code_url/project.php?id=");
    $comments_url2 = DPDatabase::escape("'>");
    $comments_url3 = DPDatabase::escape("</a>");

    $unheld_only_sql = $unheld_only ? "project_holds.state is NULL" : "1";
    dpsql_dump_themed_query("
        SELECT

            concat('$comments_url1',projectID,'$comments_url2', nameofwork, '$comments_url3') as '"
                . DPDatabase::escape(_("Title")) . "',
            authorsname as '" . DPDatabase::escape(_("Author")) . "',
            language    as '" . DPDatabase::escape(_("Language")) . "',
            genre       as '" . DPDatabase::escape(_("Genre")) . "',
            difficulty  as '" . DPDatabase::escape(_("Difficulty")) . "',
            username    as '" . DPDatabase::escape(_("Project Manager")) . "',
            FROM_UNIXTIME(modifieddate) as '"
                . DPDatabase::escape(_("Date Last Modified")) . "',
            IF(ISNULL(project_holds.state),'&nbsp;','Y') AS '" . DPDatabase::escape(_("Hold?")) . "'
        FROM projects
            LEFT OUTER JOIN project_holds USING (projectid, state)
        WHERE ($cooked_project_selector)
            AND state='{$round->project_waiting_state}'
            AND $unheld_only_sql
        ORDER BY modifieddate, nameofwork
    ");
}

function _get_num_projects_and_pages_released_in_last($name, $seconds_ago, $waiting_state)
{
    $sql = sprintf(
        "
        SELECT count(*)
        FROM project_events
        WHERE event_type = 'transition'
            AND details1 = '%s'
            AND details3 = 'via_q: %s'
            AND timestamp >= %d
        ",
        DPDatabase::escape($waiting_state),
        DPDatabase::escape($name),
        $seconds_ago
    );
    $result = DPDatabase::query($sql);
    $projects_released = mysqli_fetch_row($result)[0];

    $sql = sprintf(
        "
        SELECT sum(n_pages)
        FROM projects
        WHERE projectid in (
            SELECT projectid
            FROM project_events
            WHERE event_type = 'transition'
                AND details1 = '%s'
                AND details3 = 'via_q: %s'
                AND timestamp >= %d
        )
        ",
        DPDatabase::escape($waiting_state),
        DPDatabase::escape($name),
        $seconds_ago
    );
    $result = DPDatabase::query($sql);
    $pages_released = mysqli_fetch_row($result)[0] ?? 0;

    return [$projects_released, $pages_released];
}

function _get_num_projects_and_pages_available($round)
{
    $sql = sprintf(
        "
        SELECT count(*)
        FROM projects
        WHERE state = '%s'
        ",
        DPDatabase::escape($round->project_available_state)
    );
    $result = DPDatabase::query($sql);
    $projects_available = mysqli_fetch_row($result)[0] ?? 0;

    $sql = sprintf(
        "
        SELECT sum(n_available_pages)
        FROM projects
        WHERE state = '%s'
        ",
        DPDatabase::escape($round->project_available_state)
    );
    $result = DPDatabase::query($sql);
    $pages_available = mysqli_fetch_row($result)[0] ?? 0;

    return [$projects_available, $pages_available];
}

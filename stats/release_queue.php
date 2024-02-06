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
    global $code_url, $listing_view_modes;

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
        if (user_can_see_queue_settings()) {
            echo "<th>", html_safe(_("Projects target")), "</th>\n";
            echo "<th>", html_safe(_("Pages target")), "</th>\n";
            $columns += 2;
        }
        echo "<th>", html_safe(_("Comment")), "</th>\n";
        echo "</tr>\n";
    }

    foreach (fetch_queues_data($round, $listing_view_mode, true, null, null) as $queue_data) {
        // Because queues are ordered, there is a convention to break them up
        // into sections with a 'group queue' at the start of the section.
        if ($queue_data["is_group"]) {
            echo "<tr>";
            echo "<td colspan='$columns' class='bold'>" . html_safe($queue_data["group"]) . "</td>";
            echo "</tr>";
            continue;
        }
        $length = $queue_data["length"];
        $unheld_length = $queue_data["unheld_length"];
        if ($length === null) {
            $length = '???';
            $unheld_length = '???';
            $errors[] = sprintf(
                _('There is a syntax error in the project selector for #%1$d "%2$s"'),
                $queue_data["ordering"],
                $queue_data["name"],
            );
            $link_cell = html_safe($queue_data["name"]);
        } else {
            $ename = urlencode($queue_data["name"]);
            $link_cell = "<a href='?round_id=$round->id&amp;name=$ename'>" . html_safe($queue_data["name"]) . "</a>";
        }

        echo "<tr>";
        echo "<td>", $queue_data["ordering"], "</td>\n";
        if (!in_array($listing_view_mode, ["enabled", "populated"])) {
            echo "<td>" . html_safe($queue_data["enabled"] ? _("Yes") : "") . "</td>";
        }
        echo "<td>$link_cell</td>\n";
        echo "<td>$length</td>\n";
        echo "<td>$unheld_length</td>\n";
        if (user_can_see_queue_settings()) {
            echo "<td>", $queue_data["projects_target"], "</td>\n";
            echo "<td>", $queue_data["pages_target"], "</td>\n";
        }
        echo "<td>", html_safe($queue_data["comment"]), "</td>\n";
        echo "</tr>\n";
    }
    echo "</table>\n";

    foreach ($errors as $error) {
        echo "<p class='error'>" . html_safe($error) . "</p>";
    }
}

function _show_queue_details($round, $name, $unheld_only)
{
    global $code_url;
    $queue = fetch_queue_data_by_name($round, $name);
    if (is_null($queue)) {
        die(html_safe("No such release queue '$name' in $round->id."));
    }

    echo "<p><a href='?round_id=$round->id'>" . html_safe(sprintf(_("Back to release queues for round %s"), $round->id)) ."</a></p>\n";

    // TRANSLATORS: %s is the name of this release queue.
    echo "<h2>" . html_safe(sprintf(_('%1$s release queue: %2$s'), $round->id, $name)) . "</h2>";

    $fields = [
        _("Id") => $queue["id"],
        _("Comment") => $queue["comment"],
        _("Status") => $queue["enabled"] ? _("enabled") : _("disabled"),
        _("Current length") => $queue["length"],
        _("Length without holds") => $queue["unheld_length"],
    ];

    $cooked_project_selector = cook_project_selector($queue["project_selector"]);

    if (user_can_see_queue_settings()) {
        $fields[_("Selector")] = $queue["project_selector"];
        if ($cooked_project_selector != $queue["project_selector"]) {
            $fields[_("Filled-in")] = $cooked_project_selector;
        }
        $fields[_("Projects Target")] = $queue["projects_target"];
        $fields[_("Pages Target")] = $queue["pages_target"];
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

    foreach (fetch_queue_stats_data($round, $name) as $stats) {
        echo "<tr>";
        echo "<td class='right-align'>", $stats["days_ago"], "</td>";
        echo "<td class='right-align'>", $stats["projects_released"], "</td>";
        echo "<td class='right-align'>", $stats["pages_released"], "</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<h3>" . html_safe(_("Projects currently in queue")) . "</h3>";

    $ename = urlencode($queue["name"]);
    echo "<p>";
    if ($unheld_only) {
        echo "<a href='?round_id=$round->id&amp;name=$ename&amp;unheld_only=0'>" . html_safe("Show all projects") . "</a> | ";
        echo "<b>" . html_safe(_("Showing unheld projects")) . "</b>";
    } else {
        echo "<b>" . html_safe(_("Showing all projects")) . "</b> | ";
        echo "<a href='?round_id=$round->id&amp;name=$ename&amp;unheld_only=1'>" . html_safe("Show unheld projects") . "</a>";
    }
    echo "</p>";

    $headers = [
        _("Title"),
        _("Author"),
        _("Languages"),
        _("Genre"),
        _("Difficulty"),
        _("Project Manager"),
        _("Date Last Modified"),
        _("Hold?"),
    ];
    echo "<table class='themed theme_striped stats' style='width: 100%; table-layout: fixed'>\n";
    echo "<tr>\n";
    echo surround_and_join(array_map("html_safe", $headers), "<th>", "</th>\n", "");
    echo "</tr>\n";
    foreach(fetch_queue_projects_data($round, $queue["project_selector"], $unheld_only) as $p) {
        echo "<tr>\n";
        echo "<td><a href='$code_url/project.php?id=", attr_safe($p["projectid"]), "'>", html_safe($p["title"]), "</a></td>\n";
        echo "<td>", html_safe($p["author"]), "</td>\n";
        echo "<td>", html_safe(Project::encode_languages($p["languages"])), "</td>\n";
        echo "<td>", html_safe($p["genre"]), "</td>\n";
        echo "<td>", html_safe($p["difficulty"]), "</td>\n";
        echo "<td>", html_safe($p["username"]), "</td>\n";
        echo "<td>", date("Y-m-d H:i:s", $p["last_state_change_time"]), "</td>\n";
        echo "<td>", is_null($p["holds_state"]) ? "&nbsp;" : "Y", "</td>\n";
        echo "</tr>";
    }
    echo "</table>";
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

<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'dpsql.inc');

$username = array_get($_GET, 'username', '');
$activity_choices = _get_activity_choices();
$activity = get_enumerated_param($_GET, 'activity', null, $activity_choices, true);
$since_choices = [
    'all' => _("All"),
    'start_of_year' => _("Start of year"),
    'start_of_month_before_previous' => _("Last two months"),
];
$since = get_enumerated_param($_GET, 'since', 'start_of_month_before_previous', array_keys($since_choices));
$action_choices = ["", "grant", "revoke", "block", "unblock", "request", "deny_request_for"];
$action = get_enumerated_param($_GET, 'action', null, $action_choices, true);

require_login();

if (!user_is_a_sitemanager() && !user_is_an_access_request_reviewer()) {
    die(_("You are not authorized to invoke this script."));
}

$title = _("Access Log");
output_header($title);

echo "<h1>$title</h1>";

echo "<form method='GET'>";
echo "<table class='basic'>";
echo "<tr>";
echo "  <th>" . _("Username") . "</th>";
echo "  <td><input name='username' type='text' value='" . attr_safe($username) . "' autocapitalize='none'></td>";
echo "</tr>";
echo "<tr>";
echo "  <th>" . _("Action") . "</th>";
echo "  <td>" . _create_select($action_choices, $action, 'action') . "</td>";
echo "</tr>";
echo "<tr>";
echo "  <th>" . _("Activity") . "</th>";
echo "  <td>" . _create_select($activity_choices, $activity, 'activity') . "</td>";
echo "</tr>";
echo "<tr>";
echo "  <th>" . _("Time") . "</th>";
echo "  <td>" . _create_since_choices($since_choices, $since) . "</td>";
echo "</tr>";
echo "</table>";
echo "<input type='submit' value='" . attr_safe(_("Show")) . "'>";
echo "</form>";

echo "<hr>";

$where_username = "";
if ($username) {
    $where_username = sprintf("
        AND subject_username = '%s'
    ", DPDatabase::escape($username));
}

$where_action = "";
if ($action) {
    $where_action = "
        AND action = '$action'
    ";
}

$where_activity = "";
if ($activity) {
    $where_activity = sprintf("
        AND activity = '%s'
    ", DPDatabase::escape($activity));
}

$now = getdate();
if ($since == 'start_of_year') {
    $t_min = mktime(0, 0, 0, 1, 1, $now['year']);
} elseif ($since == 'start_of_month_before_previous') {
    $t_min = mktime(0, 0, 0, $now['mon'] - 2, 1, $now['year']);
} else {
    $t_min = 0;
}
$where_timestamp = "AND timestamp >= $t_min";

$query_limit = "";
if (!$username && !$activity && $since == 'all') {
    $query_limit = "LIMIT 200";
}

$t_min_fmt = date('Y-m-d H:i', $t_min);

// TRANSLATORS: %s is a time in the format YYYY-MM-DD HH:MM
echo "<p>" . sprintf(_("The following table shows entries in the access_log table that have occurred since %s"), $t_min_fmt) . "</p>";

if ($query_limit) {
    echo "<p class='warning'>" . _("Results have been limited to 200 entries.") . "</p>";
}

dpsql_dump_query("
    SELECT *, FROM_UNIXTIME(timestamp)
    FROM access_log
    WHERE 1
        $where_username
        $where_activity
        $where_action
        $where_timestamp
    ORDER BY timestamp DESC
    $query_limit
");

//---------------------------------------------------------------------------

function _get_activity_choices()
{
    $sql = "
        SELECT DISTINCT activity
        FROM access_log
        ORDER BY activity
    ";
    $result = DPDatabase::query($sql);

    $activities = [''];
    while ($row = mysqli_fetch_row($result)) {
        $activities[] = $row[0];
    }
    return $activities;
}

function _create_select($choices, $selected, $name)
{
    $output = "<select name='$name'>";
    foreach ($choices as $choice) {
        $checked = $choice == $selected ? "selected" : "";
        $output .= "<option value='" . attr_safe($choice) . "' $checked>$choice</option>";
    }
    $output .= "</select>";
    return $output;
}

function _create_since_choices($choices, $selected)
{
    $radio_choices = [];
    foreach ($choices as $value => $label) {
        $checked = $value == $selected ? "checked" : "";
        $radio_choices[] = "<input name='since' type='radio' value='$value' $checked>$label";
    }
    return implode("<br>", $radio_choices);
}

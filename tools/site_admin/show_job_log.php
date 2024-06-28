<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'job_log.inc');

$days_ago = get_integer_param($_GET, 'days_ago', 1, 0, 365);
$job = array_get($_GET, 'job', null);
$event = array_get($_GET, 'event', '');

$start_timestamp = time() - (60 * 60 * 24 * $days_ago);

require_login();

// check to see if the user is authorized to be here
if (!(user_is_a_sitemanager())) {
    die(_("You are not authorized to invoke this script."));
}

$title = _("Job Log");
output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>";

echo "<form method='GET'>";
echo "<table class='basic'>";
echo "<tr>";
echo "  <th>" . _("Days to show") . "</th>";
echo "  <td><input name='days_ago' type='number' value='$days_ago''></td>";
echo "</tr>";
echo "<tr>";
echo "  <th>" . _("Job") . "</th>";
echo "  <td><input name='job' type='text' value='" . attr_safe($job) . "'></td>";
echo "</tr>";
echo "<tr>";
echo "  <th>" . _("Event") . "</th>";
echo "  <td><input name='event' type='text' value='" . attr_safe($event) . "'></td>";
echo "</tr>";
echo "</table>";
echo "<input type='submit' value='" . attr_safe(_("Show")) . "'>";
echo "</form>";

echo "<hr>";

echo "<table class='themed theme_striped'>";
echo "<tr>";
echo "<th>" . _("Timestamp") . "</th>";
echo "<th>" . _("Job") . "</th>";
echo "<th>" . _("Status") . "</th>";
echo "<th>" . _("Event") . "</th>";
echo "<th>" . _("Comments") . "</th>";
echo "</tr>";
foreach (get_job_log_entries($start_timestamp, $job, $event) as $row) {
    if ($row["succeeded"] === null) {
        $status = "";
    } elseif ($row["succeeded"]) {
        $status = "succeeded";
    } else {
        $status = "<p class='error'>failed</p>";
    }
    echo "<tr>";
    echo "<td>" . date("Y-m-d H:i:s", $row["tracetime"]) . "</td>";
    echo "<td>" . $row["filename"] . "</td>";
    echo "<td>" . $status . "</td>";
    echo "<td>" . $row["event"] . "</td>";
    echo "<td>" . $row["comments"] . "</td>";
    echo "</tr>";
}
echo "</table>";

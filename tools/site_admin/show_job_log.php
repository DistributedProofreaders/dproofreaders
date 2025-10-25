<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'job_log.inc');

$days_ago = get_integer_param($_GET, 'days_ago', 1, 0, 365);
$job = $_GET['job'] ?? null;
$succeeded = get_bool_param($_GET, 'succeeded', null, true);

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
echo "  <td>";
echo "    <select name='job'>";
foreach (array_merge([_("All")], get_job_log_filenames()) as $jobname) {
    $value = $jobname == _("All") ? "" : attr_safe($jobname);
    $selected = $jobname == $job ? "selected" : "";
    echo "<option value='$value' $selected>" . html_safe($jobname) . "</option>";
}
echo "    </select>";
echo "  </td>";
echo "</tr>";
echo "<tr>";
echo "  <th>" . _("Status") . "</th>";
echo "  <td>";
echo "    <select name='succeeded'>";
foreach ([_("All") => null, _("Succeeded") => 1, _("Failed") => 0] as $name => $value) {
    $selected = $succeeded === boolval($value) ? "selected" : "";
    echo "<option value='$value' $selected>$name</option>";
}
echo "  </td>";
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
foreach (get_job_log_entries($start_timestamp, $job, /*$event*/ null, $succeeded) as $row) {
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

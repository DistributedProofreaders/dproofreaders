<?php
$relPath = "../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

$title = _("Post-Processing Mysteries");
output_header($title);

$order = get_enumerated_param($_GET, 'order', 'nameofwork', ['nameofwork', 'authorsname', 'username', 'projectid', 'modifieddate']);

echo "<h1>$title</h1>\n";

echo sprintf(_("We don't know for sure who PPd these books; if you do know, or if you did, please send an email: <a href='%1\$s'>%2\$s</a> quoting the other information in the row, including the project ID. Thanks!"), "mailto:$general_help_email_addr", "$general_help_email_addr");

//get projects that have been PPd but we don't know by whom
$psd = get_project_status_descriptor('PPd');
$result = DPDatabase::query("
    SELECT nameofwork, authorsname, username, projectid,
           from_unixtime(modifieddate) as 'LMDate'
    FROM projects
    WHERE $psd->state_selector AND postproofer = 'No Known PPer'
    ORDER BY $order ASC
");

$rownum = 0;

echo "<table class='themed'>";
echo "<tr>";
echo "<th>" . _("Number") . "</th>";
echo "<th><a href='?order=nameofwork'>" . _("Title") . "</a></th>";
echo "<th><a href='?order=authorsname'>" . _("Author") . "</a></th>";
echo "<th><a href='?order=username'>" . _("Project Manager") . "</a></th>";
echo "<th><a href='?order=projectid'>" . _("Project ID") . "</a></th>";
echo "<th><a href='?order=modifieddate'>" . _("Date Last Modified") . "</a></th>";
echo "</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    $nameofwork = $row["nameofwork"];
    $author = $row["authorsname"];
    $username = $row["username"];
    $projectID = $row["projectid"];
    $modifieddate = $row["LMDate"];

    $rownum++;

    echo "<tr>";
    echo "<td>$rownum</td>";
    echo "<td>" . html_safe($nameofwork) . "</td>";
    echo "<td style='white-space: nowrap;'>" . html_safe($author) . "</td>";
    echo "<td style='white-space: nowrap;'>$username</td>";
    echo "<td>$projectID</td>";
    echo "<td style='white-space: nowrap;'>$modifieddate</td>";
    echo "</tr>";
}

echo "</table>";

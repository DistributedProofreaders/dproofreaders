<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()

require_login();

$title = _("Post-Processing Mysteries");
output_header($title);

$order = get_enumerated_param($_GET, 'order', 'nameofwork', array('nameofwork', 'authorsname', 'username', 'projectid', 'modifieddate'));

echo "<h1>$title</h1>\n";

echo sprintf( _("We don't know for sure who PPd these books; if you do know, or if you did, please send an email: <a ref='%1\$s'>%2\$s</a> quoting the other information in the row, including the project ID. Thanks!"), "mailto:$general_help_email_addr", "$general_help_email_addr");

//get projects that have been PPd but we don't know by whom
$psd = get_project_status_descriptor('PPd');
$result = mysql_query("SELECT nameofwork, authorsname, username, 
                       projectid , from_unixtime(modifieddate) as 'LMDate'
                       FROM projects WHERE
                       $psd->state_selector
                       AND postproofer = 'No Known PPer' 
                       ORDER BY $order ASC");

$numrows = mysql_numrows($result);
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

while ($rownum < $numrows) {
    $nameofwork = mysql_result($result, $rownum, "nameofwork");
    $author = mysql_result($result, $rownum, "authorsname");
    $username = mysql_result($result, $rownum, "username");
    $projectID = mysql_result($result, $rownum, "projectid");
    $modifieddate = mysql_result($result, $rownum, "LMDate");

    $rownum++;

    echo "<tr>";
    echo "<td>$rownum</td>";
    echo "<td>$nameofwork</td>";
    echo "<td style='white-space: nowrap;'>$author</td>";
    echo "<td style='white-space: nowrap;'>$username</td>";
    echo "<td>$projectID</td>";
    echo "<td style='white-space: nowrap;'>$modifieddate</td>";
    echo "</tr>";
}

echo "</table>";

// vim: sw=4 ts=4 expandtab

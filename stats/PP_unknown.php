<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

require_login();

$title = _("Post-Processing Mysteries");
output_header($title);

$order = get_enumerated_param($_GET, 'order', 'nameofwork', array('nameofwork', 'authorsname', 'username', 'projectid', 'modifieddate'));

echo "<br><br><h2>$title</h2><br>\n";

echo "<br>\n";

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

echo "<table cols = \"6\" border =\"1\">";
echo "<td><b>Number</b></td>
      <td><b><a href =\"PP_unknown.php?order=nameofwork\">" . _("Title") . "</b></td>
      <td><b><a href =\"PP_unknown.php?order=authorsname\">" . _("Author") . "</b></td>
      <td><b><a href =\"PP_unknown.php?order=username\">" . _("Project Manager") . "</b></td>
      <td><b><a href =\"PP_unknown.php?order=projectid\">" . _("Project ID") . "</b></td>
      <td><b><a href =\"PP_unknown.php?order=modifieddate\">" . _("Date Last Modified") . "</a></b></td><tr>";

$index = 0;
while ($rownum < $numrows) {
    $nameofwork = mysql_result($result, $rownum, "nameofwork");
    $author = mysql_result($result, $rownum, "authorsname");
    $username = mysql_result($result, $rownum, "username");
    $projectID = mysql_result($result, $rownum, "projectid");
    $modifieddate = mysql_result($result, $rownum, "LMDate");

    $rownum++;

    echo "<td>$rownum</td>
          <td width=\"200\">$nameofwork</td><td>$author</td><td>$username</td><td>$projectID</td><td>$modifieddate</td><tr>";
}

echo "</table>";

// vim: sw=4 ts=4 expandtab

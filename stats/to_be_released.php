<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

require_login();

$title = _("Books To Be Released");
output_header($title);

echo "<h1>$title</h1>\n";

$order = get_enumerated_param(
        $_GET, 'order', 'default', array('default', 'username', 'modifieddate') );

if ($order == 'default') {
    $order ='nameofwork';
}

//get projects that have been checked out
$result = mysql_query("SELECT nameofwork, username, modifieddate, language, genre
                     FROM projects
                     WHERE state = '".PROJ_P1_WAITING_FOR_RELEASE."'
                     ORDER BY $order ASC");

$numrows = mysql_numrows($result);
$rownum = 0;

echo "<table class='themed striped'>\n";

echo "<tr>\n";
echo "<th>"._("Index")."</th>
      <th>"._("Name of Work")."</th>
      <th><a href =\"to_be_released.php?order=username\">"._("Project Manager")."</a></th>
      <th><a href =\"to_be_released.php?order=modifieddate\">"._("Date Last Modified")."</a></th>
      <th>"._("Language")."</th>
      <th>"._("Genre")."</th>
      </tr>";

$index = 0;
while ($rownum < $numrows) {
    $nameofwork = mysql_result($result, $rownum, "nameofwork");
    $username = mysql_result($result, $rownum, "username");
    $modifieddate = mysql_result($result, $rownum, "modifieddate");
    $language = mysql_result($result, $rownum, "language");
    $genre = mysql_result($result, $rownum, "genre");

    $today = getdate($modifieddate);
    $month = $today['month'];
    $mday = $today['mday'];
    $year = $today['year'];
    $datestamp = "$month $mday,$year";
    $rownum++;

    echo "<tr>";
    echo "<td>$rownum</td>
          <td width=\"200\">$nameofwork</td><td>$username</td><td>$datestamp</td><td>$language</td><td>$genre</td>
          </tr>\n";
}

echo "</table>";

// vim: sw=4 ts=4 expandtab

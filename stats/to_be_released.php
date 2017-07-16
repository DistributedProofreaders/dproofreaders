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
$result = mysqli_query(DPDatabase::get_connection(), "
    SELECT nameofwork, username, modifieddate, language, genre
    FROM projects
    WHERE state = '".PROJ_P1_WAITING_FOR_RELEASE."'
    ORDER BY $order ASC
");


echo "<table class='themed theme_striped'>\n";

echo "<tr>\n";
echo "<th>"._("Index")."</th>
      <th>"._("Name of Work")."</th>
      <th><a href =\"to_be_released.php?order=username\">"._("Project Manager")."</a></th>
      <th><a href =\"to_be_released.php?order=modifieddate\">"._("Date Last Modified")."</a></th>
      <th>"._("Language")."</th>
      <th>"._("Genre")."</th>
      </tr>";

$rownum = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $nameofwork = $row["nameofwork"];
    $username = $row["username"];
    $modifieddate = $row["modifieddate"];
    $language = $row["language"];
    $genre = $row["genre"];

    $today = getdate($modifieddate);
    $month = $today['month'];
    $mday = $today['mday'];
    $year = $today['year'];
    $datestamp = "$month $mday,$year";
    $rownum++;

    echo "<tr>";
    echo "<td>$rownum</td>";
    echo "<td>$nameofwork</td>";
    echo "<td style='white-space: nowrap;'>$username</td>";
    echo "<td style='white-space: nowrap;'>$datestamp</td>";
    echo "<td>$language</td>";
    echo "<td>$genre</td>";
    echo "</tr>\n";
}

echo "</table>";

// vim: sw=4 ts=4 expandtab

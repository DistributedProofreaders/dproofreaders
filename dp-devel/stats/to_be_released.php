<?
$relPath="../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

theme(_("Books To Be Released"), "header");

if ($order == 'default') {
    $order ='nameofwork';
}

//get projects that have been checked out
$result = mysql_query("SELECT nameofwork, username, modifieddate, language, genre
                     FROM projects
                     WHERE state = '".PROJ_PROOF_FIRST_WAITING_FOR_RELEASE."'
                     ORDER BY '$order' ASC");

$numrows = mysql_numrows($result);
$rownum = 0;

echo "<table cols = \"3\" border =\"1\">";
echo "<td><b>"._("Index")."</b></td><td><b>"._("Name of Work")."</b></td>
      <td><b><a href =\"to_be_released.php?order=username\">"._("Project Manager")."</b></td>
      <td><b><a href =\"to_be_released.php?order=modifieddate\">"._("Date Last Modified")."</a></b></td><td><b>"._("Language")."</b></td><td><b>"._("Genre")."</b></td><tr>";

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

    echo "<td>$rownum</td>
          <td width=\"200\">$nameofwork</td><td>$username</td><td>$datestamp</td><td>$language</td><td>$genre</td><tr>";
}

echo "</table>";
theme("","footer");
?>

<?
$relPath="../pinc/";
include($relPath.'dp_main.inc');
include($relPath.'bookpages.inc');
include($relPath.'showavailablebooks.inc');
include($relPath.'project_states.inc');
include($relPath.'page_states.inc');
include_once($relPath.'theme.inc');

theme("Books Checked Out for Post Processing", "header");


    //get projects that have been checked out
    $result = mysql_query("SELECT nameofwork, txtlink, username, modifieddate, language, genre
                     FROM projects
                     WHERE state = 'waiting_1'
                     ORDER BY '$order' ASC");

    $numrows = mysql_numrows($result);
    $rownum = 0;

    echo "<html><body><table cols = \"3\" border =\"1\">";
    echo "<td><b>Index</b></td><td><b>Name of Work</b></td>
          <td><b><a href =\"to_be_released.php?order=username\">Project Manager</b></td>
          <td><b><a href = \"to_be_released.php?order=modifieddate\">Date Last 
Modified</a></b></td><td><b>Language</b></td><td><b>Genre</b></td><tr>";
                     
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
    echo "<td>$rownum</td><td width 
=\"200\">$nameofwork</td><td>$username</td><td>$datestamp</td><td>$language</td><td>$genre</td><tr>";
    
   }
    
echo "</table></body></html>";
theme("","footer");
?>

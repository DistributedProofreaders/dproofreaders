<?
$relPath="../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

theme("Books Checked Out for Post Processing", "header");

if ($order == 'default'){
       $order ='checkedoutby, modifieddate';
  }

       echo "<a href =\"pp_checkedout.php\">Default Sort Order </a>is Checked Out To and then Date Last Modified";

    //get projects that have been checked out
    $result = mysql_query("SELECT nameofwork, txtlink, checkedoutby, modifieddate
                     FROM projects
                     WHERE state = '".PROJ_POST_FIRST_CHECKED_OUT."'
                     ORDER BY '$order' ASC");

    $numrows = mysql_numrows($result);
    $rownum = 0;

    echo "<html><body><table cols=\"3\" border=\"1\">";
    echo "<td><b>#</b></td><td><b>Name of Work</b></td>
          <td><b><a href =\"pp_checkedout.php?order=checkedoutby\">Checked Out To</b></td>
          <td><b><a href = \"pp_checkedout.php?order=modifieddate\">Date Last Modified</a></b></td><td>User Last
Login</td><tr>";

    $index = 0;

   while ($rownum < $numrows) {
    $nameofwork = mysql_result($result, $rownum, "nameofwork");
    $checkedoutby = mysql_result($result, $rownum, "checkedoutby");
    $modifieddate = mysql_result($result, $rownum, "modifieddate");

    //get users last login date
    $userresult = mysql_query("SELECT last_login
                     FROM users
                     WHERE username = '$checkedoutby'");

$lastlogin = mysql_result($userresult,0,"last_login");


//calc last modified date for project
    $today = getdate($modifieddate);
    $month = $today['month'];
    $mday = $today['mday'];
    $year = $today['year'];
    $datestamp = "$month $mday, $year";

//calc last login date for user
    $today = getdate($lastlogin);
    $month = $today['month'];
    $mday = $today['mday'];
    $year = $today['year'];
    $lastlogindate = "$month $mday, $year";

    $rownum++;
    echo "<td>$rownum</td><td width=\"200\">$nameofwork</td>
	   <td>$checkedoutby</td><td>$datestamp</td><td>$lastlogindate</td><tr>";

   }
echo "</table></body></html>";
theme("","footer");
?>

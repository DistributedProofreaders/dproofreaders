<?
$relPath="../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

$state = ( isset($_GET['state']) ? $_GET['state'] : PROJ_POST_FIRST_CHECKED_OUT );
if ( $state == PROJ_POST_FIRST_CHECKED_OUT )
{
	$activity = 'Post Processing';
}
elseif ( $state == PROJ_POST_SECOND_CHECKED_OUT )
{
	$activity = 'Post Processing Verification';
}
else
{
	echo "checkedout.php: bad value for state: '$state'";
	exit;
}

$url_base = "checkedout.php?state=$state";

theme("Books Checked Out for $activity", "header");

if (isset($_GET['order'])) {
   if ( $_GET['order'] == "default") {
       $orderclause = ' ORDER BY checkedoutby, modifieddate ASC';
   } else {
       $orderclause = ' ORDER BY '.$_GET['order'].' ASC';
   }
}
else $orderclause = "";

       echo "<a href =\"$url_base\">Default Sort Order </a>is Checked Out To and then Date Last Modified";

    //get projects that have been checked out
    $result = mysql_query("SELECT nameofwork, txtlink, checkedoutby, modifieddate
                     FROM projects
                     WHERE state = '$state'
                     $orderclause");

    $numrows = mysql_numrows($result);
    $rownum = 0;

    echo "<table cols=\"3\" border=\"1\">";
    echo "<td><b>#</b></td><td><b>Name of Work</b></td>
          <td><b><a href =\"$url_base&order=checkedoutby\">Checked Out To</b></td>
          <td><b><a href = \"$url_base&order=modifieddate\">Date Last Modified</a></b></td><td>User Last
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
echo "</table>";
theme("","footer");
?>

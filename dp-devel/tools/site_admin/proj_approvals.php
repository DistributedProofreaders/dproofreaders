<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'projectinfo.inc');
//include_once($relPath.'project_edit.inc');
//$projectinfo = new projectinfo();
//include_once('projectmgr.inc');
//include_once('page_table.inc');

theme("Copyright Approval", "header");

if (!$site_supports_metadata)
{
    echo '$site_supports_metadata is false, so exiting.';
    exit;
}

if (!user_is_a_sitemanager())
{
    echo _('You are not authorized to invoke this script.');
    exit;
}

//----------------------------------------------------------------------------------
if (isset($_GET['update']))
{
//update project approval status
     if ($_GET['metadata'] =='approved')
     {
     $statuschange = 'project_new_app';
     }
     else
     {
     $statuschange = 'project_new_unapp';     
     }

     $result = mysql_query("UPDATE projects SET state = '$statuschange' WHERE projectid = '$update'");


}

echo "<table border=1>\n";
      // Header row
		echo "<tr>\n";
		echo "    <td align='center' colspan='4'><b>Books Waiting for Copyright Approval</b></td><tr></tr>\n";
            echo "    <td align='center' colspan='4'>The following books need to be approved/disapproved for copyright 
clearance.</td><tr></tr>\n";
		echo "    <td align='center' colspan='1'><b>Title</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Author</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Clearance Line</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Approved/Disapproved</b></td>\n";
		echo "</tr>\n";

      $result = mysql_query("SELECT projectid, nameofwork, authorsname, clearance, state FROM projects WHERE state = 
'project_new_waiting_app'");
	$numrows = mysql_num_rows($result);
	$rownum = 0;

      while ($rownum < $numrows) {
           $projectid = mysql_result($result, $rownum, "projectid");
           $state = mysql_result($result, $rownum, "state");
           $name = mysql_result($result, $rownum, "nameofwork");
           $author = mysql_result($result, $rownum, "authorsname");
           $clearance = mysql_result($result, $rownum, "clearance");

	if ($rownum % 2 ) {
			$row_color = $theme['color_mainbody_bg'];
		} else {
			$row_color = $theme['color_navbar_bg'];
		}
		
      echo "<tr bgcolor='$row_color'>";
      echo "<td align='right'><a href = \"../project_manager/project_detail.php?project=$projectid\">$name</a></td>\n";
      echo "<td align='right'>$author</td>\n";
      echo "<td><input type='text' size='67' name='clearance' value='$clearance'></td>";
      echo "<td><form action = \"proj_approvals.php?project=$projectid\"><input type ='hidden' name ='update' value 
='$projectid'>";

      echo "Approved<input type='radio' name='metadata' value='approved'>
            Disapproved<input type='radio' name='metadata' value='disapproved'>
            <INPUT TYPE=SUBMIT VALUE=\"update\"></td></form>";

      $rownum++;
      echo "</tr>";
	}

//echo "</table>";
echo "<br>";
		echo "<tr></tr>\n";
		echo "<tr></tr>\n";
		echo "<tr></tr>\n";
		echo "<tr></tr>\n";
		echo "<tr></tr>\n";
		echo "<tr></tr>\n";


echo "</table>";
echo "</center>";
echo "<br>";
theme("","footer");
?>


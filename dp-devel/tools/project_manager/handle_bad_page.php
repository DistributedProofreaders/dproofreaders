<?
$relPath="./../../pinc/";
$phpBBPath="./../../phpBB2/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'html_main.inc');

//Get variables from projectmgr.php to use for form
$reason_list = array('','Image Missing','Image Mismatch','Corrupted Image','Missing Text','Text Mismatch','Other');
$projectID = $_GET['projectid'];
$fileID = $_GET['fileid'];
$imageName = $_GET['imagename'];

//Find out information about the bad page report
$result = mysql_query("SELECT * FROM $projectID WHERE fileid=$fileID");
$imageName = mysql_result($result,0,"image");
$state = mysql_result($result,0,"state");
$b_User = mysql_result($result,0,"b_user");
$b_code = mysql_result($result,0,"b_code");
if ($state == 31) { $state = 9; } elseif ($state == 41) { $state = 19; }

//Get the user id of the reporting user to be used for private messaging
$result = mysql_query("SELECT * FROM phpbb_users WHERE username=$b_User");
$b_UserID = mysql_result($result,0,"user_id");

//Display form
if ($action == "") {
$htmlC->startHeader("Bad Page Report");
$htmlC->startBody(0,1,0,0);
$tb=$htmlC->startTable(0,0,0,1);
$tr=$htmlC->startTR(0,0,1);
$td1=$htmlC->startTD(2,0,2,0,"center",0,0,1);
$td2=$htmlC->startTD(1,0,0,0,"left",0,0,1);
$td3=$htmlC->startTD(0,0,0,0,"center",0,0,1);
$td4=$htmlC->startTD(1,0,2,0,"center",0,0,1);
$td5=$htmlC->startTD(0,0,2,0,"center",0,0,1);
echo "<form action='badpage.php' method='post'>";
echo "<input type='hidden' name='projectID' value='$projectID'>";
echo "<input type='hidden' name='fileID' value='$fileID'>";
echo "<input type='hidden' name='state' value='$state'>";
echo $tb;
echo $tr.$td1;
echo "<B>Bad Page Report</B>";
echo $tr.$td2;
echo "<strong>Username:</strong>";
echo $td3;
echo "$b_user (<a href='".$phpBBPath."privmsg.php?mode=post&u=$b_UserID'>Private Message</a>)";
echo $tr.$td2;
echo "<strong>Reason:</strong>";
echo $td3;
echo $reason_list[$b_code];
echo $tr.$td2;
echo "<strong>Info:</strong>";
echo $td3;
echo "<a href='displayimage.php?project=$projectID&imagefile=$imagename' target='_new'>View Image</a> | <a href='downloadproofed.php?project=$projectID&fileid=$fileid&state=$state' target='_new'>View Text</a>";
echo $tr.$td2;
echo "<strong>What to do:&nbsp;&nbsp;</strong>";
echo $td3;
echo "<input name='action' value='fixed' type='radio'>Fixed&nbsp;<input name='action' value='bad' type='radio'>Bad Report&nbsp;<input name='action' value='unfixed' checked type='radio'>Not Fixed&nbsp;";
echo $tr.$td1;
echo "<input type='submit' VALUE='Continue'>";
echo "</td></tr></table></form></div></center></body></html>";
} else {

//Get variables passed from form
$projectID = $_POST['projectID'];
$fileID = $_POST['fileID'];
$state = $_POST['state'];

//If the PM fixed the problem or stated the report was bad update the database to reflect
if (($action == "fixed") || ($action == "bad")) {
if ($state == 31) { $state = 2; } elseif ($state = 41) { $state = 12; }
//$result = mysql_query("UPDATE $projectID SET b_user='', b_code='', state=$state WHERE fileid=$fileID");
}

//Redirect the user back to the project detail page.
header("Location: projectmgr.php?project=$projectID");
}
?>

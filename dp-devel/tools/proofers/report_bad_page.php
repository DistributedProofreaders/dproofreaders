<?
$relPath="./../../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'html_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'page_states.inc');

if ($_POST['submitted'] != 'true') {
$reason_list = array('','Image Missing','Image Mismatch','Corrupted Image','Missing Text','Text Mismatch','Other');
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
echo "<input type='hidden' name='fileid' value='".$_POST['fileid']."'>";
echo "<input type='hidden' name='projectname' value='".$_POST['projectname']."'>";
echo "<input type='hidden' name='badState' value='$badState'>";
echo "<input type='hidden' name='proofstate' value='".$_POST['proofstate']."'>";
echo "<input type='hidden' name='submitted' value='true'>";
echo $tb;
echo $tr.$td1;
echo "<B>Submit a Bad Page Report</B>";
echo $tr.$td2;
echo "<strong>Reason:</strong>";
echo $td3;
echo "<select name='reason'>";
for ($i=0;$i<count($reason_list);$i++)  { echo "<option value='$i'>$reason_list[$i]</option>"; }
echo "</select>";
echo $tr.$td2;
echo "<strong>What to Do:</strong>";
echo $td3;
echo "<input name='redirect_action' value='proof' type='radio'>Continue Proofing<input name='redirect_action' value='quit' checked type='radio'>Quit Proofing";
echo $tr.$td1;
echo "<input type='submit' VALUE='Submit Report'>";
echo $tr.$td5;
echo "<B>Note:</B>If this report causes a project to be marked<br> bad you will be redirected to your personal page.";
echo "</td></tr></table></form></div></center></body></html>";
} else {

//Update the page the user was working on to reflect a bad page.
$result = mysql_query("UPDATE ".$_POST['projectname']." SET state='".$_POST['badState']."', b_user='$pguser', b_code=".$_POST['reason']." WHERE fileid='".$_POST['fileid']."'");

//Find out how many pages have been marked bad
$totalBad = mysql_num_rows(mysql_query("SELECT * FROM ".$_POST['projectname']." WHERE state='".BAD_FIRST."' OR state='".BAD_SECOND."'"));

//If $totalBad >= 10 check to see if there are more than 3 unique reports. If there are mark the whole project as bad
if ($totalBad >= 10) {
$result = mysql_query("SELECT COUNT(DISTINCT(b_user)) FROM ".$_POST['projectname']." WHERE state='".BAD_FIRST."' OR state='".BAD_SECOND."'");
$uniqueBadPages = mysql_result($result,0);
if ($uniqueBadPages >= 3) {
if($_POST['badState']==bad_first) {
$result = mysql_query("UPDATE projects SET state='".PROJ_PROOF_FIRST_BAD_PROJECT."' WHERE projectid='".$_POST['projectname']."'");
} else {
$result = mysql_query("UPDATE projects SET state='".PROJ_PROOF_SECOND_BAD_PROJECT."' WHERE projectid='".$_POST['projectname']."'");
}
$advisePM = 1;
} }

//Get the email address of the PM
$result = mysql_query("SELECT * FROM projects WHERE projectID='".$_POST['projectname']."'");
$PMusername = mysql_result($result,0,"username");
$nameofwork = mysql_result($result,0,"nameofwork");
$result = mysql_query("SELECT * FROM users WHERE username='$PMusername'");
$PMemail = mysql_result($result,0,"email");

//If the project has been shut down advise PM otherwise advise PM that the page has been marked bad
if ($advisePM == 1) {
$message = "*****This is an automated email*****\n\n------------------------------------\n\nThe project you are managing, $nameofwork (Project ID: ".$_POST['projectname'].") has been shut down.  This is due to at least 10 users, with at least 3 unique users, reporting errors or problems with this project.  Please visit the Project Manager page to view a list of your bad projects and make any necessary changes.  You will then be able to put the project back up on the site.\n\nThank You!\nDistributed Proofreaders";
$subject = "Project Shut Down";
} else {
$message = "*****This is an automated email*****\n\n------------------------------------\n\nThere has been a page marked as bad in the project you are managing, $nameofwork (Project ID: ".$_POST['projectname'].").  Please visit the Project Manager page to view the reason it was marked as bad by the user.  You will then be able to make any needed changes and put the page back up for proofing.  If 10 pages are marked bad by at least 3 unique users the project will be automatically shut down.\n\nThank You!\nDistributed Proofreaders";
$subject = "Page Marked as Bad";
}

//Send the email to the PM
mail($PMemail, $subject, $message, "From: no-reply@texts01.archive.org <no-reply@texts01.archive.org>\r\n"); 

//Redirect the user to either continue proofing if project is still open or back to their personal page
if (($_POST['redirect_action'] == "proof") && ($advisePM != 1)) { 
  $frame1 = "proof_frame.php?project={$_POST['projectname']}&amp;proofstate={$_POST['proofstate']}";
  metarefresh(0,$frame1,'Bad Page Report','Continuing Proofing....');
} else {
  $frame1 = "projects.php?project={$_POST['projectname']}&amp;proofstate={$_POST['proofstate']}";
  metarefresh(0,$frame1,'Quit Proofing','Exiting proofing interface....');
}
}
?>
<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');

//Get variables from POST form
$projectID = $_POST['projectname'];
$fileID = $_POST['fileid'];
$reason = $_POST['reason'];
$badLimit = 10;
$unique = 3;
$reason_list = array('Image Missing','Image Mismatch','Corrupted Image','Missing Text','Text Mismatch','Other');
$username = $userP['username'];

//Determine which page to display -- Form or DB submit
if ($_POST['submitted'] != 'true') {
echo "<html><head><title>Bad Page Report</title></head><body>";
echo "<form action='badpage.php' method='post'>";
echo "<input type='hidden' name='fileid' value='$fileID'>";
echo "<input type='hidden' name='projectname' value='projectID'>";
echo "<input type='hidden' name='submitted' value='true'>";
echo "<select name='reason'>";
for ($i=0;$i<count($reason_list);$i++)  { echo "<option value='$i'>$reason_list[$i]</option>"; }
echo "</select>&nbsp;&nbsp;<input type='submit' value='Submit Report'></form></body></html>";
} else {
//Find out what round the page is currently in
$result = mysql_query("SELECT state FROM $projectID WHERE fileid=$fileID");
$currentState = mysql_result($result,0,"state");
if (in_array($currentState,array(32,35,38,39)) { $badState = 31; }
if (in_array($currentState,array(40,42,45,48,49)) { $badState = 41; }

//Update the page the user was working on to reflect a bad page.
$result = mysql_query("UPDATE $projectID SET state=$badState, reason=$reason, reporting_user=$username WHERE fileid=$fileID");

//Find out how many pages have been marked bad
$totalBad = mysql_num_rows(mysql_query("SELECT * FROM $projectID WHERE state=$badState"));

//If $totalBad >= $badLimit check to see if there are more than $unique unique reports
//If there are mark the whole project as bad
if ($totalBad >= $badLimit) {
$uniqueBadPages = mysql_query("SELECT COUNT(DISTINCT(reporting_user)) FROM $projectID WHERE state=$badState");
if ($uniqueBadPages >= $unique) {
$result = mysql_query("UPDATE projects SET state=$badState WHERE projectid=$projectID");
$advisePM = 1;
} }

//Get the email address of the PM
$result = mysql_query("SELECT * FROM projects WHERE projectID=$projectID");
$PMusername = mysql_result($result,0,"username");
$nameofwork = mysql_result($result,0,"nameofwork");
$result = mysql_query("SELECT * FROM users WHERE username=$PMusername");
$PMemail = mysql_result($result,0,"email");

//If the project has been shut down advise PM otherwise advise PM that the page has been marked bad
//TODO Post a note in the forum if a topic is in there
if ($advisePM == 1) {
$message = "*****This is an automated email*****\n\n------------------------------------\n\nThe project you are managing, $nameofwork (Project ID: $projectID) has been shut down.  This is due to at least $badLimit users, with at least $reportingUsers unique users, reporting errors or problems with this project.  Please visit the Project Manager page to view a list of your bad projects and make any necessary changes.  You will then be able to put the project back up on the site.\n\nThank You!\nDistributed Proofreaders";
$subject = "Project Shut Down";
} else {
$message = "*****This is an automated email*****\n\n------------------------------------\n\nThere has been a page marked as bad in the project you are managing, $nameofwork (Project ID: $projectID).  Please visit the Project Manager page to view the reason it was marked as bad by the user.  You will then be able to make any needed changes and put the page back up for proofing.  If $badLimit pages are marked bad by at least $reportingUsers unique users the project will be automatically shut down.\n\nThank You!\nDistributed Proofreaders";
$subject = "Page Marked as Bad";
}

//Send the email to the PM
mail($PMemail, $subject, $message, "From: no-reply@texts01.archive.org <no-reply@texts01.archive.org>\r\n"); 

//Redirect back to the personal page
echo "proof_per.php";
}
?>
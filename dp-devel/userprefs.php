<?
$relPath="./pinc/";
include($relPath.'dp_main.inc');

//TODO Add in code to change password as well.

//TODO Make pretty

//TODO Find a way to do project listing SELECTED

$uid = "userID3df6639b42b57";

if ($_POST["insertdb"] == "") {
$result=mysql_query("SELECT * FROM users WHERE id='$uid'");
$real_name = mysql_result($result,0,"real_name");
$email = mysql_result($result,0,"email");
$email_updates = mysql_result($result,0,"email_updates");
$project_listing = mysql_result($result,0,"project_listing");

echo "<form action='userprefs.php' method='post'>";
echo "<center>Preferences Page</center><br><br>";
echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
echo "<tr>";
echo "<td width='21%'>Name:</td>";
echo "<td width='79%'><input type='text' name='real_name' value='$real_name'></td>";
echo "</tr>";
echo "<tr>";
echo "<td width='21%'>Email:</td>";
echo "<td width='79%'><input type='text' name='email' value='$email'></td>";
echo "</tr>";
echo "<tr>";
echo "<td width='21%'>Email Updates:</td>";
if ($email_updates == "no") {
echo "<td width='79%'><input type='checkbox' name='email_updates' value='yes'>Yes&nbsp;&nbsp;<input type='checkbox' checked name='email_updates' value='no'>No</td>";
} else {
echo "<td width='79%'><input type='checkbox' checked name='email_updates' value='yes'>Yes&nbsp;&nbsp;<input type='checkbox' name='email_updates' value='no'>No</td>";
}
echo "</tr>";
echo "<tr>";
echo "<td width='21%'>Show projects from:</td>";
echo "<td width='79%'>";

echo "<input type='radio' name='project_listing' value='first'>First Round&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<input type='radio' name='project_listing' value='second'>Second Round&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<input type='radio' name='project_listing' value='both' checked>Both Rounds&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<input type='radio' name='project_listing' value='none'>None</td>";

echo "</tr>";
echo "</table>";
echo "<input type='hidden' name='insertdb' value='true'><br><br>";
echo "<input type='hidden' name='user_id' value='$uid'>";
echo "<center><input type='submit' value='Submit'></center>";
echo "</form>";
} else {
$user_id = $_POST['user_id'];
$real_name = $_POST['real_name'];
$email = $_POST['email'];
$email_updates = $_POST['email_updates'];
$project_listing = $_POST['project_listing'];
$result = mysql_query("UPDATE users SET real_name=$real_name, email=$email, email_updates=$email_updates, project_listing=$project_listing WHERE id=$user_id");
}

?>
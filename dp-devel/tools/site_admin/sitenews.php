<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
theme("Site News Update", "header");

echo "<br>";

$result = mysql_query("SELECT * FROM users WHERE username = '$pguser'");

if (mysql_result($result,0,"sitemanager") == "yes") {

if ($_GET['action'] == "add") {
$message = strip_tags($_POST['message'], '<a><b><i><u><font>');
$message = nl2br($message);
$date_posted = date(U);
$insert_news = mysql_query("INSERT INTO news (uid, date_posted, message) VALUES (NULL, '$date_posted', '$message')");
header("Location: sitenews.php");
}

elseif ($_GET['$action'] == "view") {
$uid = $_GET['uid'];
$result = mysql_query("SELECT * FROM news WHERE uid = $uid");
$date_posted = date("l, F jS, Y",mysql_result($result,0,'date_posted'));
echo "<b>$date_posted</b><br>";
echo mysql_result($result,0,"message");
echo "<br><br><a href='javascript:history.back()'>Go Back...</a>";
}

elseif ($_GET['action'] == "delete") {
$uid = $_GET['uid'];
$result = mysql_query("DELETE FROM news WHERE uid=$uid");
header("Location: sitenews.php");
}

elseif ($_GET['action'] == "edit_update") {
$message = $_POST['message'];
$message = strip_tags($_POST['message'], '<a><b><i><u><font>');
$message = nl2br($message);
$uid = $_POST['uid'];
$result = mysql_query("UPDATE news SET message='$message' WHERE uid=$uid");
header("Location: sitenews.php");
}

else {
$action = "add";
$submit_query = "Add Site Update";
if ($_GET['action'] == "edit") {
$uid = $_GET['uid'];
$result = mysql_query("SELECT * FROM news WHERE uid=$uid");
$message = mysql_result($result,0,"message"); 
$action = "edit_update";
$submit_query = "Edit Site Update";
}
echo "<form action='sitenews.php?action=$action' method='post'>";
echo "<center><textarea name='message' cols=50 rows=5>$message</textarea><br><input type='submit' value='$submit_query' name='submit'></center><br><br>"; 
echo "<input type='hidden' name='uid' value='$uid'></form>";
$result = mysql_query("SELECT * FROM news ORDER BY uid DESC");
while($row = mysql_fetch_array($result)) {
$date_posted = date("l, F jS, Y",$row['date_posted']);
echo "[<a href='sitenews.php?action=view&uid=".$row['uid']."')'>View</a>]&nbsp;[<a href='sitenews.php?action=edit&uid=".$row['uid']."'>Edit</a>]&nbsp;[<a href='sitenews.php?action=delete&uid=".$row['uid']."'>Delete</a>] -- ";
echo $row['message']." ($date_posted)<br><br>";
}
}

} else {

echo "You are not authorized to use this form.";
}

theme("", "footer");
?>
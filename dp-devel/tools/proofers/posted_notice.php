<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
$project = $_GET['project'];
$proofstate = $_GET['proofstate'];
$result = mysql_query("SELECT * FROM usersettings WHERE username = '".$pguser."' AND setting = 'posted_notice' AND value = '".$project."')");
if (mysql_numrows($result) == 0) {
    $insert = mysql_query("INSERT INTO usersettings (username, setting, value) VALUES ('".$pguser."', 'posted_notice', '".$project."')");
} else {
    $del = mysql_query("DELETE FROM usersettings WHERE username = '".$pguser."' AND setting = 'posted_notice' AND value = '".$project."')");
}
echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0; URL=projects.php?project=$project&proofstate=$proofstate\"></head><body></body></html>";
?>

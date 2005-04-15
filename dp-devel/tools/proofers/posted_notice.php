<?
$relPath="./../../pinc/";
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');

$project = $_GET['project'];
$proofstate = $_GET['proofstate'];

$result = mysql_query("SELECT * FROM usersettings WHERE username = '".$pguser."' AND setting = 'posted_notice' AND value = '".$project."'");
if (mysql_num_rows($result) == 0) {
    $insert = mysql_query("INSERT INTO usersettings (username, setting, value) VALUES ('".$pguser."', 'posted_notice', '".$project."')");
} else {
    $del = mysql_query("DELETE FROM usersettings WHERE username = '".$pguser."' AND setting = 'posted_notice' AND value = '".$project."'");
}

metarefresh(0, "$code_url/project.php?id=$project&amp;expected_state=$proofstate", _("Posted Notice"), "");
?>

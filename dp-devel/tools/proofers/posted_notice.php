<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
$project = $_GET['project'];
$proofstate = $_GET['proofstate'];
$insert = mysql_query("INSERT INTO usersettings (username, setting, value) VALUES ('", $pguser, "', 'posted_notice', '", $project, "')");
?>
<html><head><META HTTP-EQUIV="refresh" CONTENT="0; URL=projects.php?project=$project&proofstate=$proofstate"></head><body></body></html>
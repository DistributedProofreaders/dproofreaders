<?php
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once('../includes/book.inc');
$db_Connection=new dbConnect();

$project = validate_projectID('project', @$_GET['project']);

$result = mysql_query("SELECT * FROM projects WHERE projectid = '". $project ."'");
$curProj = mysql_fetch_assoc($result);

theme(sprintf(_("%s's Details"), $curProj['nameofwork']), "header");
echo "<br><center>";

if (!empty($curProj['projectid'])) {
	showProjProfile($curProj);
}

echo "</center>";
theme("", "footer");
?>

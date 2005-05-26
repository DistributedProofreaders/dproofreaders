<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once('../includes/book.inc');
$db_Connection=new dbConnect();

$result = mysql_query("SELECT * FROM projects WHERE projectid = '".$_GET['project']."'");
$curProj = mysql_fetch_assoc($result);

theme($curProj['nameofwork']."'s Details", "header");
echo "<br><center>";

if (!empty($curProj['projectid'])) {
	showProjProfile($curProj);
}

echo "</center>";
theme("", "footer");
?>

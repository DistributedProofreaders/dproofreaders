<?
$relPath="./pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
theme("Archived Site Updates", "header");

echo "<br>";

$result = mysql_query("SELECT * FROM news ORDER BY uid DESC");
while($row = mysql_fetch_array($result)) {
$date_posted = date("l, F jS, Y",$row['date_posted']);
echo "<a name='".$row['uid']."'><b>$date_posted</b><br>".$row['message']."<br><hr align='center' width='75%'><br>";
}

theme("", "footer");

?>
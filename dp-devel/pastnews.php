<?
$relPath="./pinc/";
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

$result = mysql_query("SELECT * FROM news ORDER BY uid DESC");
while($row = mysql_fetch_array($result)) {
$date_posted = date("l, F jS, Y",$row['date_posted']);
echo "<a name='".$row['uid']."'><b>$date_posted</b><br>".$row['message']."<br><hr align='center' width='75%'><br>";
}

?>
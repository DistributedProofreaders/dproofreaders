<?
$relPath="./pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');

// Very basic display of the past news stories
//
// Sorts the news by their id's and then prints one by one.

theme("Archived Site Updates", "header");

echo "<center>Feeds: <a href='$code_url/feeds/backend.php?content=news'><img src='$code_url/graphics/xml.gif'></a>";
echo "<a href='$code_url/feeds/backend.php?content=news&type=rss'><img src='$code_url/graphics/rss.gif'></a>";

if (isset($_GET['num'])) {
    $num = " LIMIT ".$_GET['num'];
    echo " <a href='pastnews.php'>Show All News</a>";
} else $num = "";

echo "</center>";

$result = mysql_query("SELECT * FROM news ORDER BY uid DESC".$num);

$total = 1;
while($row = mysql_fetch_array($result)) {
    $date_posted = date("l, F jS, Y",$row['date_posted']);
    echo "<br><a name='".$row['uid']."'><b>$date_posted</b><br>".$row['message']."<br><hr align='center' width='75%'><br>";
}

theme("", "footer");

?>
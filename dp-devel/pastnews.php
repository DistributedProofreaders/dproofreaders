<?
$relPath="./pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');

// Very basic display of the past news stories
//
// Sorts the news by their id's and then prints one by one.
//
// Possible future improvements:
//   Show only X of the latest news
//   RSS Feed of the site news

theme("Archived Site Updates", "header");

$result = mysql_query("SELECT * FROM news ORDER BY uid DESC");

while($row = mysql_fetch_array($result)) {
    $date_posted = date("l, F jS, Y",$row['date_posted']);
    echo "<br><a name='".$row['uid']."'><b>$date_posted</b><br>".$row['message']."<br><hr align='center' width='75%'><br>";
}

theme("", "footer");

?>
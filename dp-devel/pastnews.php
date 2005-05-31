<?
$relPath="./pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');

// Very basic display of the 'hidden' news stories for the given news page
//
// Sorts the news by their id's and then prints one by one.

if (isset($_GET['news_page'])) {
    $news_page = $_GET['news_page'];
    $type_result = mysql_query("SELECT * FROM news_pages WHERE news_page_id = '$news_page'");
    if ($news_type_row = mysql_fetch_assoc($type_result)) {
        $news_type = _($news_type_row['news_type']);        
        theme("Recent Site News Items for ".$news_type, "header");
        echo "<br>";
    } else {
       echo _("Error").": <b>".$news_page."</b> "._("Unknown news_page specified, exiting.");
       exit();
    }
} else {
    echo _("No news_page specified, exiting.");
    exit();
}


// echo "<center>Feeds: <a href='$code_url/feeds/backend.php?content=news'><img src='$code_url/graphics/xml.gif'></a>";
// echo "<a href='$code_url/feeds/backend.php?content=news&type=rss'><img src='$code_url/graphics/rss.gif'></a>";

if (isset($_GET['num'])) {
    $num = " LIMIT ".$_GET['num'];
    echo " <a href='pastnews.php?news_page=$news_page'>Show All $news_type News</a>";
} else $num = "";

echo "</center>";

$result = mysql_query("
    SELECT * FROM news_items 
    WHERE news_page_id = '$news_page' AND 
        status = 'hidden'
    ORDER BY uid DESC
".$num);

$total = 1;

if (mysql_numrows($result)== 0) {
  echo "<br><br>"._("No recent news items for ").$news_type;
} else {
    while($row = mysql_fetch_array($result)) {
        $date_posted = strftime(_("%A, %B %e, %Y"),$row['date_posted']);
        echo "<br><a name='".$row['uid']."'><b>$date_posted</b><br>".$row['message']."<br><hr align='center' width='75%'><br>";
    }
}



theme("", "footer");

?>

<?php
$relPath="./pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'site_news.inc');
include_once($relPath.'misc.inc'); // get_integer_param()

require_login();

// Very basic display of the 'recent' news stories for the given news page
//
// Sorts the news by their id's and then prints one by one.

if (isset($_GET['news_page_id'])) {
    $news_page_id = $_GET['news_page_id'];
    if ( isset($NEWS_PAGES[$news_page_id]) ) {
        $news_subject = get_news_subject($news_page_id);
        output_header(sprintf(_("Recent Site News Items for %s"), $news_subject));
        echo "<br>";
    } else {
       echo _("Error").": <b>".$news_page_id."</b> "._("Unknown news_page_id specified, exiting.");
       exit();
    }
} else {
    echo _("No news_page_id specified, exiting.");
    exit();
}


$num = get_integer_param($_GET, 'num', 0, 0, NULL);
if ($num == 0)
{
    // Invoking this script with num=0 (or without
    // the 'num' parameter) means "no limit".
    $limit_clause = "";
}
else
{
    $limit_clause = "LIMIT $num";
    echo "<a href='pastnews.php?news_page_id=$news_page_id'>"
        // TRANSLATORS: %s is the news subject.
        . sprintf(_("Show All %s News"), $news_subject) . "</a>";
}

$result = mysqli_query(DPDatabase::get_connection(), sprintf("
    SELECT * FROM news_items 
    WHERE (news_page_id = '%s' OR news_page_id = 'GLOBAL') AND 
        status = 'recent'
    ORDER BY id DESC
    $limit_clause
", mysqli_real_escape_string(DPDatabase::get_connection(), $news_page_id)));

if (mysqli_num_rows($result)== 0)
{
    echo "<br><br>" . sprintf(_("No recent news items for %s"), $news_subject);
} 
else 
{
    while($news_item = mysqli_fetch_array($result)) {
        $date_posted = strftime(_("%A, %B %e, %Y"),$news_item['date_posted']);
        echo "<br><a name='".$news_item['id']."'><b>$date_posted</b><br>".$news_item['content']."<br><hr class='center-align' style='width: 75%'><br>";
    }
}
// vim: sw=4 ts=4 expandtab

<?PHP

// -----------------------------------------------------------------------------

// Echos the visible site news designated to be shown on the page
// whose filename is $web_page_url
// This value is a key into the news_pages table to determine which
// set of news items (defined by a site_news_id) to show
// If $show_header != 1, the introductory header identifying the news
// as news is suppressed

function show_site_news_for_page( $web_page_url, $show_header = 1 )
// Output the current set of enabled, non-archived site-news news-items 
// defined for the web page $web_page_url
{

    global $theme;
    global $code_url;

    $result0 = mysql_query("
        SELECT site_news_id, news_type , modifieddate
        FROM news_pages 
        WHERE display_page = '$web_page_url'
    ");

    // this is a valid page that may potentially have news items
    if ($result0 AND mysql_num_rows($result0) > 0) {

        $news_page = mysql_fetch_assoc($result0);
        $news_page_id = $news_page['site_news_id'];
        $news_type = _($news_page['news_type']);
        $date_changed = $news_page['modifieddate'];
 
        $result = mysql_query("
            SELECT date_posted, message 
            FROM news 
            WHERE display = 1 AND (archive IS NULL OR archive != 1) 
                AND (news_page_id = '$news_page_id' OR news_page_id IS NULL)
            ORDER BY ORDERING DESC
        ");

        // we have some news items to display 
        if ($result AND  mysql_num_rows ($result) > 0) {
 
            $news = mysql_fetch_assoc($result);

            if ($show_header) {
                echo "<font size='2' face='" . $theme['font_mainbody'] . "'><center><font size=4><b>";
                echo $news_type." "._(" News")."</font><br>"._("last changed ")." ".strftime(_("%A, %B %e, %Y"), $date_changed);
                echo "<br>(<a href='$code_url/pastnews.php?news_page=$news_page_id'>"._("Recent News Items")."</a>)";

                // this commented out until fuller rollout

                // echo " <a href='$code_url/feeds/backend.php?content=news'><img src='$code_url/graphics/xml.gif'></a>";
                // echo "<a href='$code_url/feeds/backend.php?content=news&type=rss'><img src='$code_url/graphics/rss.gif'></a>";

                echo "</b></font><br>";
            } else {
                echo "<center>";
            }

            echo "<br><font size='2' face='";

            echo $theme['font_mainbody'] . "'>";
            echo $news['message'];
            while  ($news = mysql_fetch_assoc($result)) {
                echo "<br><br>".$news['message'];
            }
            echo "</center></font>\n";
        }

        // give site admin and news editors a link to add/edit/show/hide/delete and otherwise manage news items for this page
        if ( user_is_a_sitemanager() or user_is_site_news_editor()) {
            echo "<center>";
            echo "<hr width='30%'>\n";
            echo "Site Admin: <a href='$code_url/tools/site_admin/sitenews.php?news_page=$news_page_id'>" . _("Update News for ") . $news_type. "</a>";
            echo "</center>";
        }

        echo "<br>\n";
    }
}


function random_news_item_for_page( $web_page_url)
// Output a randomly selected news item from the current set of 
// non-displayed, non-archived site-news news-items 
// defined for the web page $web_page_url
{

    global $theme;
    global $code_url;

    $result0 = mysql_query("
        SELECT site_news_id, news_type , modifieddate
        FROM news_pages 
        WHERE display_page = '$web_page_url'
    ");

    // this is a valid page that may potentially have news items
    if ($result0 AND mysql_num_rows($result0) > 0) {

        $news_page = mysql_fetch_assoc($result0);
        $news_page_id = $news_page['site_news_id'];
        $news_type = _($news_page['news_type']);
        $date_changed = $news_page['modifieddate'];
 
        $result = mysql_query("
            SELECT message 
            FROM news 
            WHERE display = 0 AND (archive IS NULL OR archive != 1) 
                AND (news_page_id = '$news_page_id' OR news_page_id IS NULL)
            ORDER BY RAND() LIMIT 1
        ");

        // we have a random news items to display 
        if ($result AND mysql_num_rows ($result) == 1) {
 
            $news = mysql_fetch_assoc($result);

            echo "<center>";
            echo "<br><font size=2 face=";
            echo $theme['font_mainbody'] . ">";
            echo $news['message'];
            echo "</center></font><br>\n";
        }
        echo "<br>\n";
    }
}

// vim: sw=4 ts=4 expandtab
?>

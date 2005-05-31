<?PHP

function show_news_for_page( $news_page_id )
// Show the news block for the given page, consisting of:
// -- a header,
// -- all the 'visible' news items,
// -- a randomly-chosen 'hidden' news item,
// where the news items are designated for the given page,
// or for every page.
{
    show_site_news_for_page( $news_page_id );
    random_news_item_for_page( $news_page_id );
}

// -----------------------------------------------------------------------------

// Echos the visible site news designated to be shown on the given page.
// If $show_header != 1, the introductory header identifying the news
// as news is suppressed

function show_site_news_for_page( $news_page_id, $show_header = 1 )
// Output the current set of 'visible' site-news news-items 
// defined for the given page.
{

    global $theme;
    global $code_url;

    $result0 = mysql_query("
        SELECT news_type , modifieddate
        FROM news_pages 
        WHERE news_page_id = '$news_page_id'
    ");

    // this is a valid page that may potentially have news items
    if ($result0 AND mysql_num_rows($result0) > 0) {

        $news_page = mysql_fetch_assoc($result0);
        $news_type = _($news_page['news_type']);
        $date_changed = $news_page['modifieddate'];
 
        $result = mysql_query("
            SELECT date_posted, content 
            FROM news_items 
            WHERE status = 'visible' 
                AND (news_page_id = '$news_page_id' OR news_page_id IS NULL)
            ORDER BY ORDERING DESC
        ");

        // we have some news items to display 
        if ($result AND  mysql_num_rows ($result) > 0) {
 
            $news_item = mysql_fetch_assoc($result);

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
            echo $news_item['content'];
            while  ($news_item = mysql_fetch_assoc($result)) {
                echo "<br><br>".$news_item['content'];
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


function random_news_item_for_page( $news_page_id )
// Output a randomly selected news item from the current set of 
// 'hidden' site-news news-items 
// defined for the given page.
{

    global $theme;
    global $code_url;

    $result0 = mysql_query("
        SELECT news_type , modifieddate
        FROM news_pages 
        WHERE news_page_id  = '$news_page_id '
    ");

    // this is a valid page that may potentially have news items
    if ($result0 AND mysql_num_rows($result0) > 0) {

        $news_page = mysql_fetch_assoc($result0);
        $news_type = _($news_page['news_type']);
        $date_changed = $news_page['modifieddate'];
 
        $result = mysql_query("
            SELECT content 
            FROM news_items 
            WHERE status = 'hidden' 
                AND (news_page_id = '$news_page_id' OR news_page_id IS NULL)
            ORDER BY RAND() LIMIT 1
        ");

        // we have a random news items to display 
        if ($result AND mysql_num_rows ($result) == 1) {
 
            $news_item = mysql_fetch_assoc($result);

            echo "<center>";
            echo "<br><font size=2 face=";
            echo $theme['font_mainbody'] . ">";
            echo $news_item['content'];
            echo "</center></font><br>\n";
        }
        echo "<br>\n";
    }
}

// vim: sw=4 ts=4 expandtab
?>

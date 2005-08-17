<?PHP

function show_news_for_page( $news_page_id )
// Show the news block for the given page, consisting of:
// -- a header,
// -- all the 'current' news items,
// -- a randomly-chosen 'recent' news item,
// where the news items are designated for the given page,
// or for every page.
{
    global $theme;
    global $code_url;

    $result0 = mysql_query("
        SELECT news_type , modifieddate
        FROM news_pages 
        WHERE news_page_id = '$news_page_id'
    ") or die(mysql_error());

    if (mysql_num_rows($result0) == 0)
    {
        // The news_pages table does not have an entry for $news_page_id.
        // On the PGDP site, this would be worthy of a warning at least,
        // but elsewhere, it may just indicate that the site is not
        // interested in news for this page (or perhaps any page),
        // so we silently return.
        return;
    }

    // this is a valid page that may potentially have news items

    $news_page = mysql_fetch_assoc($result0);
    $news_type = _($news_page['news_type']);
    $date_changed = $news_page['modifieddate'];

    echo "<center>";

    // Output the set of 'current' news items 
    // defined for the given page.

    $res_current = mysql_query("
        SELECT date_posted, content 
        FROM news_items 
        WHERE status = 'current' 
            AND (news_page_id = '$news_page_id' OR news_page_id IS NULL)
        ORDER BY ORDERING DESC
    ") or die(mysql_error());

    // we have some news items to display 
    if (mysql_num_rows ($res_current) > 0) {

        if (1) {
            $header_title = sprintf( _('News for %s'), $news_type );
            echo "<font size='2' face='" . $theme['font_mainbody'] . "'>";
            echo "<b>";

            // Show header-title as an image, if available.
            global $dyn_dir, $dyn_url;
            $header_image_file = "$dyn_dir/news_header_images/$news_page_id.jpg";
            $header_image_url  = "$dyn_url/news_header_images/$news_page_id.jpg";
            if ( file_exists($header_image_file) )
            {
                echo "<img src='$header_image_url' title='$header_title' alt='$header_title'>";
            }
            else
            {
                echo "<font size=4>$header_title</font>";
            }

            echo "<br>";
            echo _("last changed ")." ".strftime(_("%A, %B %e, %Y"), $date_changed);
            echo "<br>(<a href='$code_url/pastnews.php?news_page=$news_page_id'>"._("See All Random News Items")."</a>)";

            // this commented out until fuller rollout

            // echo " <a href='$code_url/feeds/backend.php?content=news'><img src='$code_url/graphics/xml.gif'></a>";
            // echo "<a href='$code_url/feeds/backend.php?content=news&type=rss'><img src='$code_url/graphics/rss.gif'></a>";

            echo "</b></font><br>";
        }

        echo "<br><font size='2' face='";
        echo $theme['font_mainbody'] . "'>";

        $news_item = mysql_fetch_assoc($res_current);
        echo $news_item['content'];
        while  ($news_item = mysql_fetch_assoc($res_current)) {
            echo "<br><br>".$news_item['content'];
        }
        echo "</font>";

        echo "\n";
    }

    echo "<br>\n";

    // Output a randomly selected news item from the set of 
    // 'recent' news items defined for the given page.

    $res_random = mysql_query("
        SELECT content 
        FROM news_items 
        WHERE status = 'recent' 
            AND (news_page_id = '$news_page_id' OR news_page_id IS NULL)
        ORDER BY RAND() LIMIT 1
    ") or die(mysql_error());

    // we have a random news items to display 
    if (mysql_num_rows ($res_random) == 1) {

        $news_item = mysql_fetch_assoc($res_random);

        echo "<br><font size=2 face=";
        echo $theme['font_mainbody'] . ">";
        echo $news_item['content'];
        echo "</font><br>\n";
    }

    // Give site admin and news editors a link to add/edit/show/hide/delete
    // and otherwise manage news items for this page.
    if ( user_is_a_sitemanager() or user_is_site_news_editor()) {
        echo "<hr width='30%'>\n";
        echo "Site Admin: <a href='$code_url/tools/site_admin/sitenews.php?news_page=$news_page_id'>" . _("Update News for ") . $news_type. "</a>";
        echo "\n";
    }

    echo "</center>";
    echo "<br>\n";
}

// vim: sw=4 ts=4 expandtab
?>

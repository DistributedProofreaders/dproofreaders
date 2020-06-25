<?php
include_once($relPath.'stages.inc');
include_once($relPath.'misc.inc');

$NEWS_PAGES = array(
    'GLOBAL'  => _('Global'),
    'FRONT'   => _('Front Page'),
    'FAQ'     => _('FAQ Central'),
    'HUB'     => _('Activity Hub'),
    'STATS'   => _('Stats Central'),
    'SR'      => _('Smooth Reading'),
    'SR_PREV' => _('Smooth Reading Preview'),
    'PM'      => _('Project Management'),
);
foreach ( $Stage_for_id_ as $stage_id => $stage )
{
    $NEWS_PAGES[$stage_id] = $stage->name;
}

function get_news_subject( $news_page_id )
{
    global $NEWS_PAGES;
    return $NEWS_PAGES[$news_page_id];
}

// -----------------------------------------------------------------------------

function get_news_page_last_modified_date( $news_page_id )
{
    $res = mysqli_query(DPDatabase::get_connection(), "
        SELECT t_last_change
        FROM news_pages 
        WHERE news_page_id = '$news_page_id'
    ") or die(DPDatabase::log_error());

    $row = mysqli_fetch_assoc($res);
    if (!$row)
    {
        // The news_pages table does not have an entry for $news_page_id.
        return NULL;
    }
    else
    {
        return $row['t_last_change'];
    }
}

// -----------------------------------------------------------------------------

function show_news_for_page( $news_page_id )
// Show the news block for the given page, consisting of:
// -- a header,
// -- all the 'current' news items,
// -- a randomly-chosen 'recent' news item,
// where the news items are designated for the given page,
// or for every page.
{
    global $code_url;

    $user_locale = get_desired_language();

    $news_subject = get_news_subject($news_page_id);
    
    // -------------------------------------------

    // Get the set of 'current' news items 
    // defined for the given page.
    $res_current = mysqli_query(DPDatabase::get_connection(), "
        SELECT date_posted, content 
        FROM news_items 
        WHERE status = 'current' 
            AND (news_page_id = '$news_page_id' OR news_page_id = 'GLOBAL')
            AND (locale = '' OR locale = '$user_locale')
        ORDER BY ORDERING DESC
    ") or die(DPDatabase::log_error());

    // Get a randomly selected news item from the set of 
    // 'recent' news items defined for the given page.
    $res_random = mysqli_query(DPDatabase::get_connection(), "
        SELECT content 
        FROM news_items 
        WHERE status = 'recent' 
            AND (news_page_id = '$news_page_id' OR news_page_id = 'GLOBAL')
            AND (locale = '' OR locale = '$user_locale')
        ORDER BY RAND() LIMIT 1
    ") or die(DPDatabase::log_error());

    // -------------------------------------------

    if (mysqli_num_rows($res_current) > 0 || mysqli_num_rows($res_random) > 0
        || ( user_is_a_sitemanager() or user_is_site_news_editor()))
    {
        // We have at least one news item to show, or are an SA or news editor,
        // so display the news header.

        echo "<div class='news'>";

        echo "<div class='newsheader'>";
        $header_title = sprintf( _('News for %s'), $news_subject );

        // Show header-title as an image, if available.
        $header_string = get_translated_graphic_or_text("news_header", $news_page_id, $header_title);
        echo "<h2>$header_string</h2>";

        echo "<small>";
        if ( user_is_a_sitemanager() or user_is_site_news_editor())
        {
            $url = "$code_url/tools/site_admin/sitenews.php?news_page_id=$news_page_id";
            $linktext = sprintf( _("Update News for %s"), $news_subject );
            echo "<a href='$url'>$linktext</a> - ";
        }

        $date_changed = get_news_page_last_modified_date( $news_page_id );
        if ( is_null($date_changed) )
        {
            // This probably shouldn't happen.
        }
        else
        {
            // TRANSLATORS: this is a strftime-formatted string
            $formatted_date = strftime(_("%A, %B %e, %Y"), $date_changed);
            echo sprintf( _("last changed %s"), $formatted_date );
        }
        echo "</small>";
        echo "</div>"; // div.newsheader

        // -------------------------------------------
        // Output the 'current' news items, if any.

        while ($news_item = mysqli_fetch_assoc($res_current))
        {
            echo "<div class='newsitem'>";
            echo $news_item['content'];
            echo "</div>";
        }

        // -------------------------------------------
        // Output the 'random' news item, if any.

        $news_item = mysqli_fetch_assoc($res_random);
        if($news_item)
        {
            echo "<div class='newsitem'>";
            echo $news_item['content'];
            $url = "$code_url/pastnews.php?news_page_id=$news_page_id";
            $linktext = _("See all random news items");
            echo "<p><small><a href='$url'>$linktext</a></small></p>";
            echo "</div>";
        }

        echo "</div>"; // div.news
    }
}

// vim: sw=4 ts=4 expandtab

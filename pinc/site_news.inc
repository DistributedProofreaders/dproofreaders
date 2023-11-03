<?php
include_once($relPath.'stages.inc');
include_once($relPath.'misc.inc');

$NEWS_PAGES = [
    'GLOBAL' => _('Global'),
    'FRONT' => _('Front Page'),
    'FAQ' => _('FAQ Central'),
    'HUB' => _('Activity Hub'),
    'STATS' => _('Stats Central'),
    'SR' => _('Smooth Reading'),
    'SR_PREV' => _('Smooth Reading Preview'),
    'PM' => _('Project Management'),
];
foreach ($Stage_for_id_ as $stage_id => $stage) {
    $NEWS_PAGES[$stage_id] = $stage->name;
}

function get_news_subject($news_page_id)
{
    global $NEWS_PAGES;
    return $NEWS_PAGES[$news_page_id];
}

// -----------------------------------------------------------------------------

function get_news_page_last_modified_date($news_page_id)
{
    $sql = sprintf(
        "
        SELECT t_last_change
        FROM news_pages
        WHERE news_page_id = '%s'
        ",
        DPDatabase::escape($news_page_id)
    );
    $res = DPDatabase::query($sql);

    $row = mysqli_fetch_assoc($res);
    if (!$row) {
        // The news_pages table does not have an entry for $news_page_id.
        return null;
    } else {
        return $row['t_last_change'];
    }
}

// -----------------------------------------------------------------------------

/**
 * Show the news block for the given page
 *
 * Output includes
 * - a header,
 * - all the 'current' news items,
 * - a randomly-chosen 'recent' news item,
 * where the news items are designated for the given page,
 * or for every page.
 */
function show_news_for_page($news_page_id)
{
    global $code_url;

    $user_locale = get_desired_language();

    $news_subject = get_news_subject($news_page_id);

    // -------------------------------------------

    // Get the set of 'current' news items
    // defined for the given page.
    $sql = sprintf(
        "
        SELECT date_posted, header, content, item_type
        FROM news_items
        WHERE status = 'current'
            AND (news_page_id = '%s' OR news_page_id = 'GLOBAL')
            AND (locale = '' OR locale = '%s')
        ORDER BY ORDERING DESC
        ",
        DPDatabase::escape($news_page_id),
        DPDatabase::escape($user_locale)
    );
    $res_current = DPDatabase::query($sql);

    // Get a randomly selected news item from the set of
    // 'recent' news items defined for the given page.
    $sql = sprintf(
        "
        SELECT header, content, item_type
        FROM news_items
        WHERE status = 'recent'
            AND (news_page_id = '%s' OR news_page_id = 'GLOBAL')
            AND (locale = '' OR locale = '%s')
        ORDER BY RAND() LIMIT 1
        ",
        DPDatabase::escape($news_page_id),
        DPDatabase::escape($user_locale)
    );
    $res_random = DPDatabase::query($sql);

    // -------------------------------------------

    if (mysqli_num_rows($res_current) > 0 || mysqli_num_rows($res_random) > 0
        || (user_is_a_sitemanager() or user_is_site_news_editor())) {
        // We have at least one news item to show, or are an SA or news editor,
        // so display the news header.

        echo "<div class='news'>";

        echo "<div class='newsupdated'>";
        echo "<small>";
        if (user_is_a_sitemanager() or user_is_site_news_editor()) {
            $url = "$code_url/tools/site_admin/sitenews.php?news_page_id=$news_page_id";
            $linktext = _("Update News");
            echo "<a href='$url'>$linktext</a> - ";
        }

        $date_changed = get_news_page_last_modified_date($news_page_id);
        if (is_null($date_changed)) {
            // This probably shouldn't happen.
        } else {
            $formatted_date = icu_date_template("long", $date_changed);
            echo sprintf(_("last changed %s"), $formatted_date);
        }
        echo "</small>";
        echo "</div>";

        echo "<h2>" . _('News') . "</h2>";

        // -------------------------------------------
        // Output the 'current' news items, if any.

        while ($news_item = mysqli_fetch_assoc($res_current)) {
            echo "<div class='newsitem'>";

            if ($news_item['header']) {
                echo "<div class='news-header news-{$news_item['item_type']}'>";
                echo $news_item['header'];
                echo "</div>";
            }

            echo "<div class='news-content'>";
            echo $news_item['content'];
            echo "</div>";

            echo "</div>";
        }

        // -------------------------------------------
        // Output the 'random' news item, if any.

        $news_item = mysqli_fetch_assoc($res_random);
        if ($news_item) {
            echo "<div class='newsitem'>";

            if ($news_item['header']) {
                echo "<div class='news-header news-{$news_item['item_type']}'>";
                echo $news_item['header'];
                echo "</div>";
            }

            echo "<div class='news-content'>";
            echo $news_item['content'];
            echo "</div>";

            $url = "$code_url/pastnews.php?news_page_id=$news_page_id";
            $linktext = _("See all random news items");
            echo "<p><small><a href='$url'>$linktext</a></small></p>";
            echo "</div>";
        }

        echo "</div>"; // div.news
    }
}

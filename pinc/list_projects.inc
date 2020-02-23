<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'project_states.inc');
include_once($relPath.'pg.inc');

function list_projects_tersely( $where_condition, $order_clause, $limit_clause )
// List the specified projects,
// giving very brief information about each.
{
    $result = mysqli_query(DPDatabase::get_connection(), "
        SELECT nameofwork, authorsname, language, postednum
        FROM projects
        WHERE $where_condition
        $order_clause
        $limit_clause
    ");

    echo "<ul>\n";

    while (list($title,$author,$language,$postednum) = mysqli_fetch_row($result))
    {
        echo "<li><span class='title-listing'>";

        echo "<span class='book-title'>", '"', html_safe($title), '"', "</span>";
        echo "&mdash;".html_safe($author)." ($language)";
        if ( !is_null($postednum) )
        {
            echo "<br>", get_pg_catalog_link_for_etext($postednum);
        }
        echo "<br>\n";

        echo "</span>";
        echo "</li>";
        echo "\n";
    }

    echo "</ul>\n";
}

function list_projects( $where_condition, $order_clause, $url_base, $per_page = 20, $offset = 0)
// List the specified projects,
// giving brief information about each.
// url_base is the URL with the beginning of a query string, eg "foo.php?x=10&amp;" or "foo.php?"
{
    global $code_url;
    global $site_supports_corrections_after_posting;
    $sql = "
        SELECT SQL_CALC_FOUND_ROWS
            projectid,
            authorsname,
            nameofwork,
            language,
            n_pages,
            modifieddate,
            postednum,
            formats,
            state
        FROM projects
            LEFT OUTER JOIN pg_books
            ON projects.postednum=pg_books.etext_number
        WHERE $where_condition
        $order_clause
        LIMIT $per_page OFFSET $offset
    ";
    $result = mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()) . $sql);

    $numrows = mysqli_num_rows($result);

    $res_found = mysqli_query(DPDatabase::get_connection(), "SELECT FOUND_ROWS()");
    $row = mysqli_fetch_row($res_found);
    $num_found_rows = $row[0];

    if ($num_found_rows == 0) {
        echo _("There are currently no projects in this category.");
        return;
    }

    $first = $offset + 1;
    $last = $offset + $numrows;

    // Display where we are now
    // TRANSLATORS: This string is of the form: Projects <first> to <last> of <total>
    echo "<p>";
    echo sprintf(_('Projects %1$d to %2$d of %3$d'), $first, $last, $num_found_rows);
    echo "</p>\n";
    
    // Display a 'page line' with up to 5 pages from the current page
    // in either direction, and previous/next links.
    if ($num_found_rows > $per_page || $offset != 0) {
        // Display 'previous' link if we're not on the first page
        if ($offset > 0) {
            $t = _('Previous');
            $prev_offset = max(0, $offset - $per_page);
            $url = $url_base . "per_page=$per_page&amp;offset=$prev_offset";
            echo "<a href='$url'>$t</a> ";
        }
    
        $MAXPAGES = 5;
        $curpage = floor($offset/$per_page);
        $firstpage = max(0, $curpage - $MAXPAGES);
        $lastpage = min($curpage + $MAXPAGES, $num_found_rows/$per_page);
        for($i = $firstpage; $i <= $lastpage; $i++) {
            $url = $url_base . "per_page=$per_page&amp;offset=" . ($i * $per_page);
            if ($i == $curpage) {
                echo " <b>" . ($i+1) . "</b> ";
            } else {
                echo " <a href='$url'>" . ($i+1) . "</a> ";
            }
        }
    
        // Display 'next' link if we're not on the last page
        if ($offset + $per_page < $num_found_rows) {
            $t = _('Next');
            $next_offset = min($num_found_rows - 1, $offset + $per_page);
            $url = $url_base . "per_page=$per_page&amp;offset=$next_offset";
            echo " <a href='$url'>$t</a>";
        }
        echo "<br>\n";
    }
    
    echo "<ol start='$first'>";
    while ($project = mysqli_fetch_array($result))
    {
        $projectid = $project['projectid'];
        $author    = $project['authorsname'];
        $title     = $project['nameofwork'];
        $language  = $project['language'];
        $pagecount = $project['n_pages'];
        // TRANSLATORS: this is a strftime-formatted string
        $moddate   = strftime( _("%A, %B %e, %Y"), $project['modifieddate'] );
        $postednum = $project['postednum'];


        // First line
        echo "<li id='$projectid'>";
        // Counter
        // Title
        if (is_null($postednum))
        {
            echo "\"<a style=\"font-weight: bold;text-decoration:none\" href=\"$code_url/project.php?id=$projectid\">$title</a>\"";
        } else {
            echo "\"<span style=\"font-weight: bold;color:#444444\">".html_safe($title)."</span>\"";
        }
        // Author
        echo ", ", html_safe($author);
        // Language
        echo " (", $language, ")";
        echo "<br>";

        // Second line
        // Number of pages
        if ($pagecount != 0) { echo sprintf(_("%d pages"),$pagecount) . "; "; }
        // Last-modification date
        echo $moddate;
        echo "<br>";

        // Third line
        // Download info
        if ( !is_null($postednum) )
        {
            echo get_pg_catalog_link_for_etext($postednum);
            echo " [{$project['formats']}]";

            if ($site_supports_corrections_after_posting)
            {
                echo ", ";
                if ($project['state'] == PROJ_SUBMIT_PG_POSTED) {
                    echo "<a href='$code_url/tools/upload_text.php?project=$projectid&amp;stage=correct'>" . _("submit corrections") . "</a>";
                } else {
                    echo _("under review");
                }
            }
            echo "<br>";
        }
        echo "<br>";

        // ---------------------------------------------------------------------

        echo "</li>";
    }
    echo "</ol>";
}
// vim: sw=4 ts=4 expandtab

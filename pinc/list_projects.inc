<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'project_states.inc');
include_once($relPath.'pg.inc');

function list_projects_tersely($where_condition, $order_clause, $limit_clause)
// List the specified projects,
// giving very brief information about each.
{
    $sql = "
        SELECT nameofwork, authorsname, language, postednum
        FROM projects
        WHERE $where_condition
        $order_clause
        $limit_clause
    ";
    $result = DPDatabase::query($sql);

    echo "<ul>\n";

    while ([$title, $author, $language, $postednum] = mysqli_fetch_row($result)) {
        echo "<li class='title-listing'>";

        echo "<span class='book-title'>" . html_safe($title) . "</span>";
        echo ", " . html_safe($author) . " (" . html_safe($language) . ")";
        if (!is_null($postednum)) {
            echo "<br>", get_pg_catalog_link_for_etext($postednum);
        }
        echo "</li>";
        echo "\n";
    }

    echo "</ul>\n";
}

function list_projects($where_condition, $order_clause, $url_base, $per_page = 20, $offset = 0)
// List the specified projects,
// giving brief information about each.
// url_base is the URL with the beginning of a query string, eg "foo.php?x=10&amp;" or "foo.php?"
{
    global $code_url;

    // first get the number of projects
    $sql = "
        SELECT COUNT(*)
        FROM projects
            LEFT OUTER JOIN pg_books
            ON projects.postednum=pg_books.etext_number
        WHERE $where_condition
    ";
    $result = DPDatabase::query($sql);
    [$num_found_rows] = mysqli_fetch_row($result);

    if ($num_found_rows == 0) {
        echo _("There are currently no projects in this category.");
        return;
    }

    // now load the paged results
    $sql = "
        SELECT
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
    $result = DPDatabase::query($sql);

    $numrows = mysqli_num_rows($result);

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
        $curpage = floor($offset / $per_page);
        $firstpage = max(0, $curpage - $MAXPAGES);
        $lastpage = min($curpage + $MAXPAGES, ($num_found_rows - 1) / $per_page);
        for ($i = $firstpage; $i <= $lastpage; $i++) {
            $url = $url_base . "per_page=$per_page&amp;offset=" . ($i * $per_page);
            if ($i == $curpage) {
                echo " <b>" . ($i + 1) . "</b> ";
            } else {
                echo " <a href='$url'>" . ($i + 1) . "</a> ";
            }
        }

        // Display 'next' link if we're not on the last page
        if ($offset + $per_page + 1 < $num_found_rows) {
            $t = _('Next');
            $next_offset = min($num_found_rows - 1, $offset + $per_page);
            $url = $url_base . "per_page=$per_page&amp;offset=$next_offset";
            echo " <a href='$url'>$t</a>";
        }
        echo "<br>\n";
    }

    echo "<ol start='$first'>";
    while ($project = mysqli_fetch_array($result)) {
        $projectid = $project['projectid'];
        $author = $project['authorsname'];
        $title = $project['nameofwork'];
        $language = $project['language'];
        $pagecount = $project['n_pages'];
        $moddate = icu_date_template("long", $project['modifieddate']);
        $postednum = $project['postednum'];


        // First line
        echo "<li class='title-listing' id='$projectid'>";
        // Counter
        // Title
        echo "<span class='book-title'>";
        if (is_null($postednum)) {
            echo "<a href='$code_url/project.php?id=$projectid'>" . html_safe($title) . "</a>";
        } else {
            echo html_safe($title);
        }
        echo "</span>, " . html_safe($author) . " (" . html_safe($language) . ")";
        echo "<br>";

        // Second line
        // Number of pages
        if ($pagecount != 0) {
            echo sprintf(_("%d pages"), $pagecount) . "; ";
        }
        // Last-modification date
        echo $moddate;
        echo "<br>";

        // Third line
        // Download info
        if (!is_null($postednum)) {
            echo get_pg_catalog_link_for_etext($postednum);
            if ($project['formats']) {
                echo " [{$project['formats']}]";
            }
            echo "<br>";
        }
        echo "<br>";

        // ---------------------------------------------------------------------

        echo "</li>";
    }
    echo "</ol>";
}

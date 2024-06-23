<?php
include_once($relPath.'pg.inc');

/**
 * Output a list of projects giving very brief information about each
 */
function list_projects_tersely(string $metal, string $order_clause, string $limit_clause)
{
    if ($metal == "gold") {
        $where_condition = SQL_CONDITION_GOLD;
    } elseif ($metal == "silver") {
        $where_condition = SQL_CONDITION_SILVER;
    } elseif ($metal == "bronze") {
        $where_condition = SQL_CONDITION_BRONZE;
    }

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

/**
 * Output a list of projects giving brief information about each.
 *
 * url_base is the URL with the beginning of a query string, eg "foo.php?x=10&amp;" or "foo.php?"
 */
function list_projects(string $metal, string $order_clause, string $url_base, int $per_page = 20, int $offset = 0)
{
    global $code_url;

    if ($metal == "gold") {
        $join = "
            LEFT OUTER JOIN pg_books
            ON projects.postednum=pg_books.etext_number
        ";
        $join_columns = "formats,";
        $where_condition = SQL_CONDITION_GOLD;
        $group_clause = "GROUP BY postednum";
    } elseif ($metal == "silver") {
        $join = "";
        $join_columns = "";
        $where_condition = SQL_CONDITION_SILVER;
        $group_clause = "";
    } elseif ($metal == "bronze") {
        $join = "";
        $join_columns = "";
        $where_condition = SQL_CONDITION_BRONZE;
        $group_clause = "";
    }

    // first get the number of projects, we cache this value for an hour
    // so it might be a bit out of sync with the full listing, but it means
    // we don't have to do this query twice
    $num_found_rows = memoize_function("get_star_texts_count", [$metal]);
    if ($num_found_rows == 0) {
        echo _("There are currently no projects in this category.");
        return;
    }

    $sql = "
        SELECT
            projectid,
            authorsname,
            nameofwork,
            language,
            n_pages,
            modifieddate,
            postednum,
            $join_columns
            state
        FROM projects
            $join
        WHERE $where_condition
        $group_clause
        $order_clause
        LIMIT $per_page OFFSET $offset
    ";

    // now load the paged results by ordering, limiting and offsetting
    $sql .= "
    ";
    $result = DPDatabase::query($sql);

    $numrows = mysqli_num_rows($result);

    $first = $offset + 1;
    $last = $offset + $numrows;

    // Display where we are now
    echo "<p>";
    // TRANSLATORS: This string is of the form: Projects <first> to <last> of <total>
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

/**
 * Return number of books posted to PG
 *
 * This function takes into account that some books are processed as several
 * projects.
 */
function total_completed_projects()
{
    $psd = get_project_status_descriptor('posted');
    $sql = "
        SELECT COUNT(distinct postednum)
        FROM projects
        WHERE $psd->state_selector
    ";
    return mysqli_fetch_row(DPDatabase::query($sql))[0];
}

/**
 * Return the number of projects for a given star metal type.
 */
function get_star_texts_count(string $metal): int
{
    if ($metal == "bronze") {
        $state = SQL_CONDITION_BRONZE;
        return mysqli_fetch_row(DPDatabase::query("SELECT count(*) FROM projects WHERE $state"))[0];
    } elseif ($metal == "silver") {
        $state = SQL_CONDITION_SILVER;
        return mysqli_fetch_row(DPDatabase::query("SELECT count(*) FROM projects WHERE $state"))[0];
    } elseif ($metal == "gold") {
        return total_completed_projects();
    }
    throw new InvalidArgumentException("\$metal is not a valid value");
}

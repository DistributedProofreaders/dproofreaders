<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'pg.inc');
new dbConnect();

function list_projects_tersely( $where_condition, $order_clause, $limit_clause )
// List the specified projects,
// giving very brief information about each.
{
    $result = mysql_query("
        SELECT nameofwork, authorsname, language, postednum
        FROM projects
        WHERE $where_condition
        $order_clause
        $limit_clause
    ");

    echo "<br>\n";

    while (list($title,$author,$language,$postednum) = mysql_fetch_row($result))
    {
        echo "<font face='Verdana' size='1'>";

        echo "<font color='#444444'><b>", '"', $title, '"', "</b></font>";
        echo "&mdash;$author ($language)";
        if ( !is_null($postednum) )
        {
            echo "<br>", get_pg_catalog_link_for_etext($postednum);
        }
        echo "<br>\n";

        echo "</font>";
        echo "<br>";
        echo "\n";
    }
}

function list_projects( $where_condition, $order_clause, $limit_clause )
// List the specified projects,
// giving brief information about each.
{
    global $code_url;
    global $site_supports_corrections_after_posting;

    $result = mysql_query("
        SELECT *
        FROM projects
            LEFT OUTER JOIN pg_books
            ON projects.postednum=pg_books.etext_number
        WHERE $where_condition
        $order_clause
        $limit_clause
    ");

    echo "<br>\n";

    $counter = 1;
    while ($project = mysql_fetch_array($result))
    {
        $projectid = $project['projectid'];
        $author    = $project['authorsname'];
        $title     = $project['nameofwork'];
        $language  = $project['language'];
        $pagecount = $project['n_pages'];
        $moddate   = strftime( _("%A, %B %e, %Y"), $project['modifieddate'] );
        $postednum = $project['postednum'];

        echo "<font face='Verdana' size='1'>";

        {

            // First line
            echo "<a name='$projectid'></a>";
            // Counter
            echo "$counter) ";
            // Title
            echo "<font color='#444444'><b>", '"', $title, '"', "</b></font>";
            // Author
            echo ", ", $author;
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

            // ---------------------------------------------------------------------
        }

        echo "</font>";
        echo "<br>";
        echo "\n";

        $counter++;
    }
}
// vim: sw=4 ts=4 expandtab
?>

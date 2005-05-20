<?php
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'pg.inc');
new dbConnect();

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

    echo "<br>";
    echo "\n";

    $counter = 1;
    while ($project = mysql_fetch_array($result))
    {
        $projectid = $project['projectid'];

        echo "<font face='Verdana' size='1'>";

        // ---------------------------------------------------------------------
        // First line

        // echo "<a name='$projectid'></a>";

        // Counter
        echo "$counter) ";

        // Title
        echo "<font color='#444444'><b>", '"', $project['nameofwork'], '"', "</b></font>";

        // Author
        echo ", ", $project['authorsname'];

        // Language
        echo " (", $project['language'], ")";

        echo "<br>";

        // ---------------------------------------------------------------------
        // Second line

        // Number of pages
        $totalpages = $project['n_pages'];

        if ($totalpages != 0)
        {
            echo "$totalpages " . _("pages") . "; ";
        }

        // Last-modification date
        $moddate = strftime( _("%A, %B %e, %Y"), $project['modifieddate'] );
        echo $moddate;

        echo "<br>";

        // ---------------------------------------------------------------------
        // Third line

        // Download info
        $postednum = $project['postednum'];
        if ( $postednum != 6000 && $postednum != 0 )
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

        echo "</font>";
        echo "<br>";
        echo "\n";

        $counter++;
    }
}
// vim: sw=4 ts=4 expandtab
?>

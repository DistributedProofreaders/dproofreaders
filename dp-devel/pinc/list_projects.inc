<?php
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'bookpages.inc');
new dbConnect();

function list_projects( $where_condition, $order_clause, $limit_clause )
// List the specified projects,
// giving brief information about each.
{
    global $pageCountArray;
    global $code_url;

    if ( empty($pageCountArray) )
    {
        update_pageCountArray();
    }

    $result = mysql_query("
        SELECT *
        FROM projects
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
        if (0)
        {
            $res = mysql_query("SELECT COUNT(*) FROM $projectid");
            if ($res)
            {
                $totalpages = mysql_result($res,0);
            }
            else
            {
                // Probably the $projectid table is gone.
                $totalpages = 0;
            }
        }
        else
        {
            if ( isset($pageCountArray[$projectid]) )
            {
                $totalpages = $pageCountArray[$projectid]['total_pages'];
            }
            else
            {
                $totalpages = 0;
            }
        }
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
        if ( $project['postednum'] != 6000 && $project['postednum'] != 0 )
        {
            if (trim($project['ziplink'])  <> "") echo "<a href='{$project['ziplink']}'>"._("zip version")."</a>, ";
            if (trim($project['txtlink'])  <> "") echo "<a href='{$project['txtlink']}'>"._("text version")."</a>, ";
            if (trim($project['htmllink']) <> "") echo "<a href='{$project['htmllink']}'>"._("html version")."</a>, ";

            if ($project['state'] == PROJ_SUBMIT_PG_POSTED) {
                echo "<a href='$code_url/tools/upload_text.php?project=$projectid&amp;stage=correct'>" . _("submit corrections") . "</a>";
            } else {
                echo _("under review");
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

<?
$relPath="./../../pinc/";
include_once($relPath.'http_headers.inc');
include($relPath.'projectinfo.inc');
include_once($relPath.'bookpages.inc');
include_once($relPath.'echo_project_info.inc');
include_once($relPath.'gettext_setup.inc');

/* $_GET $project, $proofstate, $proofing */

include($relPath.'slim_header.inc');
slim_header(_("Project Comments"));

if (!isset($proofing))
{
    // The user arrived here in the usual way, e.g. by clicking on a project
    // name in the list of projects available for proofing.

    if ($userP['i_newwin']==1)
    {
        include($relPath.'js_newwin.inc');
    }

    include('./projects_menu.inc');

    function echo_blurb_box( $blurb )
    {
        echo "<br>";
        echo "<table width='630' bgcolor='DDDDDD'>";
        echo "<tr><td align='center'>";
        echo $blurb;
        echo "</td></tr>";
        echo "</table>";
        echo "<br>";
        echo "\n";
    }

    project_update_page_counts( $project );

    $num_pages_available = Project_getNumAvailablePagesInRound( $project, $proofstate );
    if ( $num_pages_available == 0 )
    {
        $top_blurb = $bottom_blurb =
            _("Round Complete")
            . "<br>"
            . _("There are no pages available for proofreading.");
    }
    else
    {
        // If there's any proofreading to be done, this is the link to use.
        $url = "proof_frame.php?project=$project&amp;proofstate=$proofstate"; // encoded for use in attribute
        $label = _("Start Proofreading");
        $proofreading_link = "<b><a href='$url' target='proofframe'>$label</a></b>";

        // When were the project comments last modified?
        $res = mysql_fetch_assoc(mysql_query("SELECT modifieddate FROM projects WHERE projectid = '$project'"));
        $comments_timestamp = $res['modifieddate'];
        $comments_time_str = strftime(_("%A, %B %e, %Y at %X"), $comments_timestamp);
        $comments_last_modified_blurb = _("Project Comments last modified:") . " " . $comments_time_str;

        // Other possible components of blurbs:
        $please_scroll_down = _("Please scroll down and read the Project Comments for any special instructions <b>before</b> proofreading!");
        $the_link_appears_below = _("The 'Start Proofreading' link appears below the Project Comments");
        $comments_have_changed =
            "<font color='red'>"
            . "<b>"
            . _("Project Comments have changed!")
            . "</b>"
            . "</font>";

        // ---

        $bottom_blurb =
            $comments_last_modified_blurb
            . "<br>"
            . $proofreading_link;

        // Has the user saved a page of this project since the comments were
        // last changed?  If not, it's unlikely they've seen the revised comments.
        $prd = get_PRD_for_project_state($proofstate);
        $res = mysql_query("
            SELECT {$prd->time_column_name}
            FROM $project
            WHERE state='{$prd->page_save_state}' AND {$prd->user_column_name}='$pguser'
            ORDER BY {$prd->time_column_name} DESC
            LIMIT 1
        ");
        if (mysql_num_rows($res) == 0)
        {
            // The user has not saved any pages for this project.
            $top_blurb =
                $please_scroll_down
                . "<br>"
                . $comments_last_modified_blurb
                . "<br>"
                . $the_link_appears_below;
        }
        else
        {
            // The user has saved a page for this project.
            $my_latest_save_timestamp = mysql_result($res,0,$prd->time_column_name);

            if ($my_latest_save_timestamp < $comments_timestamp)
            {
                // The latest page-save was before the comments were revised.
                // The user probably hasn't seen the revised project comments.
                $top_blurb =
                    $comments_have_changed
                    . "<br>"
                    . $please_scroll_down
                    . "<br>"
                    . $comments_last_modified_blurb
                    . "<br>"
                    . $the_link_appears_below;
            }
            else
            {
                // The latest page-save was after the comments were revised.
                // We'll assume that the user has read the comments.
                $top_blurb =
                    $please_scroll_down
                    . "<br>"
                    . $comments_last_modified_blurb
                    . "<br>"
                    . $proofreading_link;
            }
        }
    }

    echo_blurb_box( $top_blurb );

    echo_project_info( $project, $proofstate, TRUE );

    echo_blurb_box( $bottom_blurb );

    include('./projects_menu.inc');
}
else
{
    // The user arrived here by clicking on "View Project Comments"
    // in the proofing interface.

    echo_project_info( $project, $proofstate, FALSE );
    echo "<BR>";

    echo "<p><p><b>";
    echo _("This information has been opened in a separate browser window, feel free to leave it open for reference or close it.");
    echo "</b>";
}

echo "</BODY></HTML>";
// vim: sw=4 ts=4 expandtab
?>

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

    project_update_page_counts( $project );

    $num_pages_available = Project_getNumAvailablePagesInRound( $project, $proofstate );
    $pages_are_available = ( $num_pages_available > 0 );

    $top_menu = 1;

    // Get Last Page Date Proofed By Current User

    $prd = get_PRD_for_project_state($proofstate);
    $proofdate = mysql_query("
        SELECT {$prd->time_column_name}
        FROM $project
        WHERE state='{$prd->page_save_state}' AND {$prd->user_column_name}='$pguser'
        ORDER BY {$prd->time_column_name} DESC
        LIMIT 1
    ");
    if (mysql_num_rows($proofdate)!=0) {
        $my_last_page_date = mysql_result($proofdate,0,$prd->time_column_name);
    } else $my_last_page_date = 0;

    $project_comments = mysql_fetch_assoc(mysql_query("SELECT modifieddate FROM projects WHERE projectid = '$project'"));
    $comments_last_modified = $project_comments['modifieddate'];

    include('./projects_menu.inc');
    $top_menu = 0;

    echo "<br>";
    echo "<i>";
    echo _("Please scroll down and read the Project Comments for any special instructions <b>before</b> proofreading!");
    echo "</i>";
    echo "<br>";
    echo "<br>";

    echo_project_info( $project, $proofstate, TRUE );
    echo "<BR>";

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

<?
$relPath="./../../pinc/";
include_once($relPath.'http_headers.inc');
include($relPath.'projectinfo.inc');
include_once($relPath.'bookpages.inc');
include_once($relPath.'echo_project_info.inc');
include_once($relPath.'gettext_setup.inc');

project_update_page_counts( $project );

$num_pages_available = Project_getNumAvailablePagesInRound( $project, $proofstate );
$pages_are_available = ( $num_pages_available > 0 );


/* $_GET $project, $proofstate, $proofing */

include($relPath.'slim_header.inc');
slim_header(_("Project Comments"));

if (!isset($proofing) && $userP['i_newwin']==1)
{include($relPath.'js_newwin.inc');}
if (!isset($proofing)) {
    $top_menu = 1;

    if ($proofstate==PROJ_PROOF_FIRST_AVAILABLE) {
	$wTime="round1_time"; $wState=SAVE_FIRST; $wName="round1_user";
    } else {
	$wTime="round2_time"; $wState=SAVE_SECOND; $wName="round2_user";
    }

    // Get Last Page Date Proofed By Current User

    $proofdate=mysql_query("SELECT $wTime FROM $project WHERE state='$wState' AND $wName='$pguser' ORDER BY $wTime DESC LIMIT 1");
    if (mysql_num_rows($proofdate)!=0) {
        $my_last_page_date = mysql_result($proofdate,0,$wTime);
    } else $my_last_page_date = 0;

    $project_comments = mysql_fetch_assoc(mysql_query("SELECT modifieddate FROM projects WHERE projectid = '$project'"));
    $comments_last_modified = $project_comments['modifieddate'];

    include('./projects_menu.inc');
    $top_menu = 0;
?>
<br><i><? echo _("Please scroll down and read the Project Comments for any special instructions <b>before</b> proofreading!"); ?></i><br>
<br>

<?
}
    echo_project_info( $project, $proofstate, !isset($proofing) );
    echo "<BR>";

    if (!isset($proofing)) {
        include('./projects_menu.inc');
    } else {
        echo"<p><p><b> "._("This information has been opened in a separate browser window, feel free to leave it open for reference or close it.")."</b>";
    }
?>
</BODY></HTML>

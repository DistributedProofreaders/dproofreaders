<?
$relPath="./../../pinc/";
include($relPath.'projectinfo.inc');
include_once($relPath.'bookpages.inc');
include_once($relPath.'echo_project_info.inc');

$projectinfo = new projectinfo();
if ($proofstate==PROJ_PROOF_FIRST_AVAILABLE) {
	update_avail_pages($project, " = '".AVAIL_FIRST."'");
	$projectinfo->update_avail($project, PROJ_PROOF_FIRST_AVAILABLE);
} else {
	update_avail_pages($project, " = '".AVAIL_SECOND."'");
	$projectinfo->update_avail($project,PROJ_PROOF_SECOND_AVAILABLE);
}


/* $_GET $project, $proofstate, $proofing */

include($relPath.'doctype.inc');
echo "$docType\r\n<HTML><HEAD><TITLE> "._("Project Comments")."</TITLE>";
if (!isset($proofing) && $userP['i_newwin']==1)
{include($relPath.'js_newwin.inc');}
echo "</HEAD><BODY>";
if (!isset($proofing)) {
    include('./projects_menu.inc');
?>
<br><i><? echo _("Please scroll down and read the Project Comments for any special instructions <b>before</b> proofing!"); ?></i><br>
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

<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'echo_project_info.inc');
include_once($relPath.'project_edit.inc');
include_once('projectmgr.inc');
include_once('page_table.inc');

// About versions: Full vs. Summary
// $link_to_other_version is a message about what version this is and a link to the other one
// $current_version is _('Summary version') or _('Full version') and is displayed to the user
//   (only in the document title at the time I write this)
// $page_type is 'Summary' or 'Full' and used by code in checking
//   what to display on this page
// $other_type is 'Summary' or 'Full' and used by code as
//   a parameter in the link to the other version

if ( !isset($_GET['type']) || $_GET['type'] == 'Summary' || $_GET['type'] == '' ) {
	$link_to_other_version = _('This is the Summary Version of the Project Details page for this project.<br>'.
		'Also available is the <a href="%1$s">Full Version</a>.');
	$current_version = _('Summary version');
	$page_type = "Summary";
	$other_type = "Full";
} else {
	$link_to_other_version = _('This is the Full Version of the Project Details page for this project.<br>'.
		'Also available is the <a href="%1$s">Summary Version</a>.');
	$current_version = _('Full version');
	$page_type = "Full";
	$other_type = "Summary";
}

// don't show the stats column
$no_stats=1;
theme("Project Details ($current_version)", "header");

$projectid = $_GET['project'];

// the following exits if the user is neither PP nor project manager of this project.
// nor a project mgr (doesn't need to be this project) nor several other kinds of manager.
if (! user_is_PP_of( $projectid)) {
	abort_if_not_manager();
}

// insert url into $link_to_other_version
$link_to_other_version = sprintf($link_to_other_version, "project_detail.php?project=$projectid&type=$other_type");

// project is readonly except for PM of project, site managers,
// and facilitators (plus hidden identies therein)
$can_edit = (user_is_PM_of( $projectid) || user_is_a_sitemanager() || user_is_proj_facilitator() );

// exit unless PM of project, or sitemanager, or facilitor, or a position hidden therein.
// Note that PP of project is redundant - then can_edit would be true anyway.
if (!$can_edit && ! user_is_PP_of( $projectid))
{
	echo "
			<p>
			You do not have access to this page.
			<p>
			Back to <a href='$code_url/default.php'>home page</a>
		";
	exit();
}

// test
//$can_edit = FALSE;

if ($can_edit) {

    // this is in projectmgr.inc.
    // offers/withholds other viewing options, using mucho sql.
    echo_manager_header( 'project_detail_page' );

    // remind where/how to ftp projects.
        echo "
        <p>
        ".sprintf(_("Reminder for uploads:
        host=<b>%s</b>
        account=<b>%s</b>
        password=<b>%s</b>"),$uploads_host,$uploads_account,$uploads_password)."
        </p>
        ";
}

$result = mysql_query("SELECT state FROM projects WHERE projectid='$projectid'");
$state = mysql_result($result, 0);

echo "<center>";
echo $link_to_other_version . '<br><br>';

// displays project header info, like proj manager, page counts, etc.
// Many queries.
echo_project_info( $projectid, 'proj_post', 0 );

// if user has access, offer to edit project.
if ($can_edit) {
	echo "<p><a href='editproject.php?project=$projectid'>"._("Edit the above information")."</a></p>";
}

//if new project enable uploading of tpNv info
// is this used anywhere?
if ($site_supports_metadata) {
	if ($state == PROJ_NEW){
        echo "<br>\n";
        echo "<form method='get' action='add_files.php'>\n";
        echo "<input type='hidden' name='project' value='$projectid'>\n";
        echo "<input type='hidden' name='tpnv' value='1'>\n";
        echo sprintf(_("<b>Add Title Page and Verso from %s Account</b>"),$uploads_account);
        echo "<br>\n";
                echo _("directory: ");
                echo "<input type='text' name='source_dir'>";
                echo sprintf(_(" (defaults to %s)")."$projectid/tpnv");
        echo "<br>\n";
        echo "<input type='submit' value='Add'>";
        echo "<br>\n";
        echo "</form>\n";
      }       
}

// confusing list of qualifications.
// probably allows incorporating files into project if proofing hasn't begun yet.
if (
	($state == PROJ_NEW && ! $site_supports_metadata)
	|| ( $site_supports_metadata && ($state == PROJ_NEW_APPROVED || $state == PROJ_NEW_FILE_UPLOADED) )
	|| $state == PROJ_P1_UNAVAILABLE
)
{
	echo "<br>\n";
	echo "<form method='get' action='add_files.php'>\n";
	echo "<input type='hidden' name='project' value='$projectid'>\n";
	//Used to be different how a SA would load projects.  Changed due to no reason of having this anymore
	//but left just in case we need it in the future.
	//if (user_is_a_sitemanager()) {
	//	echo "Add Text From projects Folder";
	//	echo "<input type='hidden' name='source_dir' value=''>\n";
	//} else {
		echo sprintf(_("Add Text+Images from %s Account"),$uploads_account);
		echo "<br>\n";
		echo _("directory: ");
		echo "<input type='text' name='source_dir'>";
		echo sprintf(_(" (defaults to %s )"),$projectid);
	//}
	echo "<br>\n";
	echo "<input type='submit' value='Add'>";
	echo "<br>\n";
	echo "</form>\n";
}

// -----------------------------------------------------------------------------

echo "<h3>"._("Page Summary")."</h3>\n";

// page counts by state.
$res = mysql_query( "SELECT count(*) AS total_num_pages FROM $projectid" );
$total_num_pages = mysql_result($res,0,'total_num_pages');

// This could be made faster (by doing one SQL query outside the loop)
// but I'm not sure the savings would be noticeable.
echo "<table border=0>\n";
foreach ($PAGE_STATES_IN_ORDER as $page_state)
{
	$res = mysql_query( "
		SELECT count(*) AS num_pages
		FROM $projectid
		WHERE state='$page_state'
	");
	$num_pages = mysql_result($res,0,'num_pages');
	if ( $num_pages != 0 )
	{
		echo "<tr><td align='right'>$num_pages</td><td>".sprintf(_("in %s"),$page_state)."</td></tr>\n";
	}
}
echo "<tr><td colspan='2'><hr></td></tr>\n";
echo "<tr><td align='right'>$total_num_pages</td><td align='center'>"._("pages total")."</td></tr>\n";
echo "</table>\n";

// -----------------------------------------------------------------------------


// only show full page table details in "Full" mode

if ($page_type == "Full") {
	include_once('detail_legend.inc');
	echo _("N.B. It is <b>strongly</b> recommended that you view page differentials by right-clicking on a diff link and opening the link in a new window or tab.")."<br>\n";

    // second arg. indicates to show size of image files.
	echo_page_table($projectid, 1);
}

echo '<br>' . $link_to_other_version;

echo "</center>";

echo "<br>";
theme("","footer");
?>

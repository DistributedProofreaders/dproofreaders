<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'echo_project_info.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'project_edit.inc');
$projectinfo = new projectinfo();
include_once('projectmgr.inc');
include_once('page_table.inc');

$no_stats=1;
theme("Project Details", "header");

$projectid = $_GET['project'];
if (! user_is_PP_of( $projectid)) {
	abort_if_not_manager();
}




$can_edit = (user_is_PM_of( $projectid) || user_is_a_site_manager());

// test
$can_edit = FALSE;

if ($can_edit) {

echo_manager_header( 'project_detail_page' );

echo "
<p>
Reminder for uploads:
host=<b>$uploads_host</b>
account=<b>$uploads_account</b>
password=<b>$uploads_password</b>
</p>
";

}

$result = mysql_query("SELECT state FROM projects WHERE projectid='$projectid'");
$state = mysql_result($result, 0);

$projectinfo->update($projectid, $state);

echo "<center>";

echo_project_info( $projectid, 'proj_post', 0 );


if ($can_edit) {

	echo "<p><a href='editproject.php?project=$projectid'>Edit the above information</a></p>";

}

//if new project enable uploading of tpNv info
if ($state == PROJ_NEW){
       echo "<br>\n";
        echo "<form method='get' action='add_files.php'>\n";
        echo "<input type='hidden' name='project' value='$projectid'>\n";
        echo "<input type='hidden' name='tpnv' value='1'>\n";
        echo "<b>Add Title Page and Verso from $uploads_account Account</b>";
        echo "<br>\n";
                echo "directory: ";
                echo "<input type='text' name='source_dir'>";
                echo " (defaults to $projectid/tpnv)";
        echo "<br>\n";
        echo "<input type='submit' value='Add'>";
        echo "<br>\n";
        echo "</form>\n";
}       

if ($state == PROJ_NEW_APPROVED || $state == PROJ_PROOF_FIRST_UNAVAILABLE || $state == PROJ_NEW_FILE_UPLOADED)
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
		echo "Add Text+Images from $uploads_account Account";
		echo "<br>\n";
		echo "directory: ";
		echo "<input type='text' name='source_dir'>";
		echo " (defaults to $projectid )";
	//}
	echo "<br>\n";
	echo "<input type='submit' value='Add'>";
	echo "<br>\n";
	echo "</form>\n";

	$something = 0;
}
elseif ( $state == PROJ_PROOF_FIRST_AVAILABLE
	|| $state == PROJ_PROOF_FIRST_WAITING_FOR_RELEASE
	|| $state == PROJ_PROOF_FIRST_BAD_PROJECT
	|| $state == PROJ_PROOF_FIRST_VERIFY
	|| $state == PROJ_PROOF_FIRST_COMPLETE )
{
	$something = 1;
}
else
{
	$something = 2;
}

// -----------------------------------------------------------------------------

echo "<h3>Page Summary</h3>\n";

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
		echo "<tr><td align='right'>$num_pages</td><td>in $page_state</td></tr>\n";
	}
}
echo "<tr><td colspan='2'><hr></td></tr>\n";
echo "<tr><td align='right'>$total_num_pages</td><td align='center'>pages total</td></tr>\n";
echo "</table>\n";

// -----------------------------------------------------------------------------

echo "<h3>Per-Page Info</h3>\n";
echo_page_table( $projectid );

// -----------------------------------------------------------------------------

if ($can_edit) {

	if ($state == PROJ_NEW || $state == PROJ_PROOF_FIRST_UNAVAILABLE ||  $state == PROJ_NEW_FILE_UPLOADED)
	{
		echo "<br><br><br>";
		echo "<a href='deletefile.php?project=$projectid'>Delete All Text</a>";
	}
}

echo "</center>";

echo "<br>";
theme("","footer");
?>

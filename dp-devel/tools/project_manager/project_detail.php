<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'project_edit.inc');
$projectinfo = new projectinfo();
include_once('projectmgr.inc');
include_once('page_table.inc');

$no_stats=1;
theme("Project Details", "header");

abort_if_not_manager();

$projectid = $_GET['project'];
abort_if_cant_edit_project( $projectid );

echo_manager_header();

?>

<p><b>Project Manager Notice:</b>

<p>
You can now specify a directory
(in the <? echo $uploads_account; ?> account)
from which to add text+images into your project.
This means that you are now free to choose the name
of the upload directory you create,
instead of having to use the project's ID.
(E.g., you might choose to give it the same name
as the corresponding directory on your local machine.)
Of course, the project's ID will still work fine
as the name of the directory, and is in fact the default
for the Add Text+Images button.

<p>
Moreover, the string you type is actually interpreted as a 'path'
(relative to the root of the <? echo $uploads_account; ?> account),
so it can be a directory within a directory.
For instance, you may find it convenient to create a personal directory
in the <? echo $uploads_account; ?> account,
and then create your project-specific directories within it.
(If you do this, it's recommended that you use your DP login name
for the name of the personal directory,

<?

$result = mysql_query("SELECT nameofwork, authorsname, language, username, state FROM projects WHERE projectid = '$projectid'");

$manager = mysql_result($result, 0, "username");
$state = mysql_result($result, 0, "state");
$name = mysql_result($result, 0, "nameofwork");
$author = mysql_result($result, 0, "authorsname");
$language = mysql_result($result, 0, "language");

$projectinfo->update($projectid, $state);

echo "<center><table border=1>";
echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan=4><b><font color='".$theme['color_headerbar_font']."' size=+1>Project Name: $name</font></b> <font color='".$theme['color_headerbar_font']."'>($projectid)</font></td></tr>";
echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'>Author:</td><td>$author</td><td bgcolor='".$theme['color_navbar_bg']."'>Total Number of Master Pages:</td><td>$projectinfo->total_pages</td></tr>";
echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'>Language:</td><td>$language</td><td bgcolor='".$theme['color_navbar_bg']."'>Pages Remaining to be Proofed:</td><td>$projectinfo->availablepages</td></tr>";
echo "</table>";

if ($state == PROJ_NEW || $state == PROJ_PROOF_FIRST_UNAVAILABLE)
{
	echo "<br>\n";
	echo "<form method='get' action='add_files.php'>\n";
	echo "<input type='hidden' name='project' value='$projectid'>\n";
	if ($userP['sitemanager'] == "yes") {
		echo "Add Text From projects Folder";
		echo "<input type='hidden' name='source_dir' value=''>\n";
	} else {
		echo "Add Text+Images from $uploads_account Account";
		echo "<br>\n";
		echo "directory: ";
		echo "<input type='text' name='source_dir'>";
		echo " (defaults to $projectid )";
	}
	echo "<br>\n";
	echo "<input type='submit' value='Add'>";
	echo "<br>\n";
	echo "</form>\n";

	echo "<a href='deletefile.php?project=$projectid'>Delete All Text</a>";
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


echo "<h3>Per-Page Info</h3>\n";
echo_page_table( $projectid );

echo "</center>";

echo "<br>";
theme("","footer");
?>

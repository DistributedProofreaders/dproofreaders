<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');
include($relPath.'project_edit.inc');
include($relPath.'page_states.inc');

$projectid = $_GET['project'];

abort_if_cant_edit_project( $project );

if ($userP['sitemanager'] == 'yes')
{
	// It's assumed that anyone with site-manager privileges
	// would upload their files directly into the projects area.
	// Maybe this should be an option, as sometimes a site-manager
	// may want to upload to dpscans.
	$source_area = $projects_dir;
}
else
{
	$source_area = $uploads_dir;
}

echo "<pre>\n";

$source_project_dir = "$source_area/$projectid";
$dest_project_dir   = "$projects_dir/$projectid";

if ($source_project_dir != $dest_project_dir)
{
	echo "copying page-images from\n";
	echo "    $source_project_dir\n";
	echo "to\n";
	echo "    $dest_project_dir\n";
	system("cp $source_project_dir/*.png $dest_project_dir");
}

echo "\n";
echo "For each text file in\n";
echo "    $source_project_dir\n";
echo "adding a row to the $projectid table...\n";
foreach ( glob("$source_project_dir/*.txt") as $txt_file_path )
{
	// basename() corrupts its first argument!
	// So don't pass $txt_file_path to it directly.
	$file_base = basename(strval($txt_file_path),'.txt');
	echo "    $file_base.txt\n";

	// We assume that there is a correspondingly-named .png file.
	// If there isn't, it will discovered at the verification stage?
	$image_file_name = "$file_base.png";

	// I'm pretty sure that fileid serves no architectural purpose.
	// That is, it's redundant, given the 'image' field.
	// However, until the code is changed to use 'image' instead of 'fileid',
	// we have to define it, and presumably make it unique over a project.
	// The former code used a random-number generator, which guaranteed
	// unpredictability, but not uniqueness.

	$sql_command = "
		INSERT INTO $projectid
		SET
			fileid      = '$file_base',
			image       = '$image_file_name',
			master_text = LOAD_FILE('$txt_file_path'),
			round1_time = NOW(),
			state       = '".AVAIL_FIRST."'
	";
	// echo $sql_command, "\n";
	$res = mysql_query($sql_command);
	if (!$res) echo "        ", mysql_error(), "\n";
}

echo "</pre>\n";
echo "<hr>\n";
echo "Return to <a href='projectmgr.php?project=$project'>Project Page</a>.\n";

?>

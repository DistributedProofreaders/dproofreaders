<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');
include($relPath.'project_edit.inc');
include($relPath.'page_states.inc');

$projectid = $_GET['project'];

abort_if_cant_edit_project( $projectid );

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

if ( $_GET['source_dir'] == '' )
{
	$source_dir = $projectid;
}
else
{
	$source_dir = stripslashes($_GET['source_dir']);
	// Prevent sneaky parent-link tricks.
	if ( ereg( '\.\.', $source_dir ) )
	{
		echo "Source directory '$source_dir' is not acceptable.";
		echo "<hr>\n";
		echo "Return to <a href='projectmgr.php?project=$projectid'>Project Page</a>.\n";
		return;
	}
}

echo "<pre>\n";

$source_project_dir = "$source_area/$source_dir";
$dest_project_dir   = "$projects_dir/$projectid";

// Rather than performing commands from an arbitrary location,
// using absolute paths, e.g.
//     system("cp $source_project_dir/*.png $dest_project_dir");
//     glob("$source_project_dir/*.txt"),
// we chdir into $source_project_dir and do *local* commands from there.
// That way, we don't have to worry about any shell-special or
// glob-special characters in $source_project_dir.
// (There don't appear to be any chdir-special characters.)
$r = chdir($source_project_dir);
if ( !$r )
{
	echo "Directory '$source_project_dir' does not exist, or is inaccessible.\n";
	echo "<hr>\n";
	echo "Return to <a href='projectmgr.php?project=$projectid'>Project Page</a>.\n";
	return;
}


if ($source_project_dir != $dest_project_dir)
{
	echo "copying page-images from\n";
	echo "    $source_project_dir\n";
	echo "to\n";
	echo "    $dest_project_dir\n";
	system("cp *.png $dest_project_dir");
}

$n_txt_files_found = 0;
$n_rows_inserted = 0;

$now = time();

echo "\n";
echo "For each text file in\n";
echo "    $source_project_dir\n";
echo "adding a row to the $projectid table...\n";
foreach ( glob("*.txt") as $txt_file_name )
{
	$n_txt_files_found++;

	// basename() corrupts its first argument!
	// So don't pass $txt_file_name to it directly.
	$file_base = basename(strval($txt_file_name),'.txt');
	echo "    $file_base.txt\n";

	// We assume that there is a correspondingly-named .png file.
	// If there isn't, it will discovered at the verification stage?
	$image_file_name = addslashes("$file_base.png");

	// I'm pretty sure that fileid serves no architectural purpose.
	// That is, it's redundant, given the 'image' field.
	// However, until the code is changed to use 'image' instead of 'fileid',
	// we have to define it, and presumably make it unique over a project.
	// The former code used a random-number generator, which guaranteed
	// unpredictability, but not uniqueness.

	// We need to pass an absolute path to LOAD_FILE.
	$txt_file_path = addslashes("$source_project_dir/$txt_file_name");
	$sql_command = "
		INSERT INTO $projectid
		SET
			fileid      = '$file_base',
			image       = '$image_file_name',
			master_text = LOAD_FILE('$txt_file_path'),
			round1_time = $now,
			state       = '".AVAIL_FIRST."'
	";
	// echo $sql_command, "\n";
	$res = mysql_query($sql_command);
	if ($res)
	{
		$n_rows_inserted++;
	}
	else
	{
		echo "        ", mysql_error(), "\n";
	}
}

echo "\n";
echo "$n_txt_files_found text-files found.\n";
echo "$n_rows_inserted rows inserted into table.\n";

echo "</pre>\n";
echo "<hr>\n";
echo "Return to <a href='projectmgr.php?project=$projectid'>Project Page</a>.\n";

?>

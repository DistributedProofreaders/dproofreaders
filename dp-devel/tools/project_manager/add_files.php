<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include($relPath.'project_edit.inc');
include($relPath.'page_ops.inc');

$projectid = $_GET['project'];

abort_if_cant_edit_project( $projectid );

$source_area = $uploads_dir;
// Formerly, we assumed that anyone with site-manager privileges
// would upload their files directly into the projects area,
// but we discontinued that practice.
// if (user_is_a_sitemanager())
// {
// 	$source_area = $projects_dir;
// }

$loading_tpnv = ( isset($_GET['tpnv']) && $_GET['tpnv'] == '1' );

if ( $_GET['source_dir'] == '' )
{
     //if they are uploading tpnv files then get them from /tpnv 
     if ( $loading_tpnv )
     {
         $source_dir = "$projectid/tpnv";
         $source_area = $uploads_dir;
     }
     else
     {
	   $source_dir = $projectid;
     }
}
else
{
	$source_dir = stripslashes($_GET['source_dir']);
	// Prevent sneaky parent-link tricks.
	if ( ereg( '\.\.', $source_dir ) )
	{
		echo "Source directory '$source_dir' is not acceptable.";
		echo "<hr>\n";
		echo "Return to <a href='$code_url/project.php?id=$projectid'>Project Page</a>.\n";
		return;
	}
}

$isZipFile = (substr($source_dir, -4) == ".zip");
if ($isZipFile) {
	$source_dir = substr($source_dir, 0, strpos($source_dir, ".zip"));
}

echo "<pre>\n";

$source_project_dir = "$source_area/$source_dir";

//if they are uploading tpnv files then put them in /tpnv 
if ( $loading_tpnv )
{
      $dest_project_dir = "$projects_dir/$projectid/tpnv";
             if (!file_exists($dest_project_dir)) { 
                mkdir("$dest_project_dir", 0777);
                chmod("$dest_project_dir", 0777);
             }
}
else
{
      $dest_project_dir   = "$projects_dir/$projectid";
}


if ($isZipFile == 1) {
	if (!file_exists($source_project_dir)) {
		mkdir("$source_project_dir", 0777);
		chmod("$source_project_dir", 0777);
	}

	exec("unzip -o -j ".$source_project_dir.".zip -d $source_project_dir");
	unlink($source_project_dir.".zip");
}

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
	echo "Return to <a href='$code_url/project.php?id=$projectid'>Project Page</a>.\n";
	return;
}



if ($source_project_dir != $dest_project_dir)
{
	echo "copying page-images from\n";
	echo "    $source_project_dir\n";
	echo "to\n";
	echo "    $dest_project_dir\n";
	system("cp *.png $dest_project_dir");
	system("cp *.jpg $dest_project_dir");
}

if ( ! $loading_tpnv )
{
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
	$image_file_name = "$file_base.png";

	// I'm pretty sure that fileid serves no architectural purpose.
	// That is, it's redundant, given the 'image' field.
	// However, until the code is changed to use 'image' instead of 'fileid',
	// we have to define it, and presumably make it unique over a project.
	// The former code used a random-number generator, which guaranteed
	// unpredictability, but not uniqueness.

	// We need to pass an absolute path to LOAD_FILE.
	$txt_file_path = "$source_project_dir/$txt_file_name";

	$errs = project_add_page( $projectid, $file_base, $image_file_name, $txt_file_path, $now );
	if (!$errs)
	{
		$n_rows_inserted++;
	}
	else
	{
		echo "        $errs\n";
	}
}

//update projects table and put the project into proj_new_files_uploaded
//$result = mysql_query("UPDATE projects SET state = 'project_new_uploaded' WHERE projectid = '$projectid'");

// the above waiting for new set of states to be supported everywhere; below temp retrofit
$result = mysql_query("UPDATE projects SET state = 'project_new' WHERE projectid = '$projectid'");


echo "\n";
echo "$n_txt_files_found text-files found.\n";
echo "$n_rows_inserted rows inserted into table.\n";
}

//if uploaded tpnv files set project to project_new_waiting_app
if ( $loading_tpnv )
{
$result = mysql_query("UPDATE projects SET state = 'project_new_waiting_app' WHERE projectid = '$projectid'");
}

echo "</pre>\n";
echo "<hr>\n";
echo "Return to <a href='$code_url/project.php?id=$projectid&verbosity=4'>Project Page</a>.\n";

?>

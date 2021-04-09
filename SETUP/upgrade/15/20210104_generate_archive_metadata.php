<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'archiving.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Generating missing archive project metadata..\n";

echo "\n";
echo "If you get permission errors, you may need to execute this script as\n";
echo "the user the web server is running under, eg:\n";
echo "  sudo su -s /bin/bash \ \n";
echo "      -c '/usr/bin/php 20210104_generate_archive_metadata.php' \ \n";
echo "      www-data\n";
echo "\n";

if(!is_dir($archive_projects_dir))
{
	echo "$archive_projects_dir (\$archive_projects_dir) does not exist so there's nothing to do.\n";
	exit;
}

foreach(glob("$archive_projects_dir/projectID*") as $project_dir)
{
	$metadata_filename = "$project_dir/metadata.json";
	if(file_exists($metadata_filename))
	{
		// file already exists, skip it
		continue;
	}

	preg_match("/(projectID.*)/", $project_dir, $matches);
	$projectid = $matches[1];

	echo "Generating metadata file for $projectid\n";

	$project = new Project($projectid);
	generate_project_metadata_json($project, $metadata_filename, FALSE);
}

// ------------------------------------------------------------

echo "\nDone!\n";


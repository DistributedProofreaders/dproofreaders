<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'archiving.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Generating missing archive project metadata..\n";

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

	echo "Genearting metadata file for $projectid\n";

	$project = new Project($projectid);
	generate_project_metadata_json($project, $metadata_filename, FALSE);
}

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab

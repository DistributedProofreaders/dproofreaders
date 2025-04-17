<?php

// Download a generated-on-demand zip of the
// image files in a given project directory.

$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'post_files.inc');

require_login();

$projectid = get_projectID_param($_GET, 'projectid');

[$zipfile_path, $zipfile_url] = generate_project_images_zip($projectid);

header("Location: $zipfile_url");

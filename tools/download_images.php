<?php

// Download a generated-on-demand zip of the
// image files in a given project directory.

$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');

require_login();

$projectid = get_projectID_param($_GET, 'projectid');

$zipfile_path = "$dyn_dir/download_tmp/{$projectid}_images.zip";
$zipfile_url = "$dyn_url/download_tmp/{$projectid}_images.zip";
$project_path = "$projects_dir/$projectid";

// If there's already a zip file, check if its contents are up to
// date. If they are, we can just redirect the user to it straight
// away. If it's stale, delete the zip and let it be rebuilt later.
if (file_exists($zipfile_path)) {
    $cache_stale = false;

    // List all the images in the zip file image cache
    $zipfile_modified = filemtime($zipfile_path);
    $zipfile_images = list_files_in_zip($zipfile_path);

    // List all the images in the project directory
    $proj_images = get_filelist($project_path, ['.png', '.jpg']);
    if ($proj_images === false) {
        echo "download_images.php: Could not list project images.";
        exit;
    }

    // If either list contains something not in the other, then files have
    // been added, removed or renamed in the project and the cache is stale
    if (count(array_diff($zipfile_images, $proj_images)) != 0) {
        $cache_stale = true;
    }
    if (count(array_diff($proj_images, $zipfile_images)) != 0) {
        $cache_stale = true;
    }

    // If the modification date of any of the image files in the project
    // directory is newer than that of the zip file, the cache is stale.
    foreach ($proj_images as $i) {
        if (filemtime("$project_path/$i") > $zipfile_modified) {
            $cache_stale = true;
            break;
        }
    }

    // If the cache is stale, remove it, else redirect to it.
    if ($cache_stale) {
        unlink($zipfile_path);
    } else {
        header("Location: $zipfile_url");
        exit;
    }
}

if (!is_dir($project_path)) {
    echo "download_images.php: no project directory named '$projectid'.";
    exit;
}

if (!is_dir(dirname($zipfile_path))) {
    mkdir(dirname($zipfile_path), 0777, true /* recursive */);
}

// Get a list of image filenames to feed into /usr/bin/zip on stdin
$image_files = get_filelist($project_path, ['.png', '.jpg'], /*with_path*/ true);
if ($image_files === false) {
    echo "download_images.php: Could not list project images.";
    exit;
}

create_zip_from($image_files, $zipfile_path);

header("Location: $zipfile_url");

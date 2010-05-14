<?php

// Download a generated-on-demand zip of the
// image files in a given project directory.

$relPath='../pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'site_vars.php');

$projectid = validate_projectID('projectid', @$_GET['projectid']);

$zipfile_path = "$dyn_dir/download_tmp/{$projectid}_images.zip";
$zipfile_url  = "$dyn_url/download_tmp/{$projectid}_images.zip";
$project_path = "$projects_dir/$projectid";

// If there's already a zip file, check if its contents are up to
// date. If they are, we can just redirect the user to it straight
// away. If it's stale, delete the zip and let it be rebuilt later.
if (file_exists($zipfile_path))
{
    $cache_stale = false;
    
    // List all the images in the zip file image cache
    $zipfile_modified = filemtime($zipfile_path);
    exec("zipinfo -1 $zipfile_path", $zipfile_images, $return_code);
    
    // List all the images in the project directory
    $proj_images = array();
    if ($h = opendir($project_path))
    {
        while (false !== ($i = readdir($h)))
        {
            if (endswith($i, ".png") || endswith($i, ".jpg"))
                $proj_images[] = $i;
        }
    }
    closedir($h);
    
    // If either list contains something not in the other, then files have
    // been added, removed or renamed in the project and the cache is stale
    if (count(array_diff($zipfile_images, $proj_images)) != 0)
        $cache_stale = true;
    if (count(array_diff($proj_images, $zipfile_images)) != 0)
        $cache_stale = true;
    
    // If the modification date of any of the image files in the project
    // directory is newer than that of the zip file, the cache is stale.
    foreach($proj_images as $i)
    {
        if (filemtime("$project_path/$i") > $zipfile_modified)
        {
            $cache_stale = true;
            break;
        }
    }
    
    // If the cache is stale, remove it, else redirect to it.
    if ($cache_stale)
    {
        unlink($zipfile_path);
    } else {    
        header( "Location: $zipfile_url" );
        exit;
    }
}

if (!is_dir($project_path))
{
    echo "download_images.php: no project directory named '$projectid'.";
    exit;
}

mkdir_recursive( dirname($zipfile_path), 0777 );

exec(
    "zip -n .png:.jpg -q -j $zipfile_path $project_path/*.png $project_path/*.jpg",
    $output,
    $return_code );
if ($return_code != 0)
{
    echo "download_images.php: the zip command failed.";
    exit;
}

header( "Location: $zipfile_url" );
// vim: sw=4 ts=4 expandtab
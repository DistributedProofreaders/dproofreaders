<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');

require_localhost_request();

if (!$site_supports_metadata)
{
    echo 'genthumbs.php: $site_supports_metadata is false, so exiting.';
    exit();
}

set_time_limit(90);

//this module looks for projects that do not have thumbs generated and generates them

$result = mysqli_query(DPDatabase::get_connection(), "SELECT projectid, state FROM projects WHERE state = 'project_md_first' AND thumbs = 'no' LIMIT 1");

$row = mysqli_fetch_assoc($result);
$projectid = $row["projectid"];
$state = $row["state"];

//make thumbs directory
$dest_project_dir = "$projects_dir/$projectid/thumbs";
if (!file_exists($dest_project_dir)) { 
    mkdir("$dest_project_dir", 0777);
    chmod("$dest_project_dir", 0777);
}


$result = mysqli_query(DPDatabase::get_connection(), "SELECT image FROM $projectid");

while ($row = mysqli_fetch_assoc($result)) {

    $imagename = $row["image"];

    //setup our source and destination images
    $image = "$projects_dir/$projectid/$imagename"; // name/location of original image.
    $new_image = "$projects_dir/$projectid/thumbs/$imagename"; // name/location of generated thumbnail.

    //'find' the source image
    $src_img = ImageCreateFrompng($image);

    //Get original image width and height
    $src_width = imagesx($src_img);
    $src_height = imagesy($src_img);

    //Our target output width
    $dest_width = 125; //width thumbnail (image will scale down completely to this width)

    //Calculate the aspect ratio of the original
    $aspect_ratio = $src_height / $src_width;

    //Calculate our output height to maintain the aspect ration of the original
    $dest_height = $dest_width * $aspect_ratio;

    //process the image
    //$dest_img = imagecreatetruecolor($dest_width,$dest_height); 
    $dest_img = imagecreate($dest_width,$dest_height); 
    imagecopyresampled($dest_img, $src_img, 0, 0, 0 ,0, $dest_width, $dest_height, $src_width, $src_height); 

    //write it out
    imagepng($dest_img, $new_image); 

    //clean up memory
    imagedestroy($src_img); 
    imagedestroy($dest_img);
}

//update projects table to indicate thumbs have been gen'ed
$result = mysqli_query(DPDatabase::get_connection(), "UPDATE projects SET thumbs = 'yes' WHERE projectid = '$projectid'");

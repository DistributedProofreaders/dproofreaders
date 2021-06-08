<?php
$relPath = "../../pinc/";
include_once($relPath."base.inc");
include_once($relPath."slim_header.inc");
include_once($relPath."Project.inc");

require_login();

$projectid = get_projectID_param($_GET, 'project', true) ??
    get_projectID_param($_GET, 'projectid', true);
$imagefile = get_page_image_param($_GET, 'imagefile', true) ??
    get_page_image_param($_GET, 'page', true);


$title = "Page moved";
slim_header($title);

echo "<h1>$title</h1>";

echo "<p>This page has moved. Please update your bookmarks.</p>";

echo "<p>The following link will get you to the new location with whatever parameters you passed in.</p>";
$url = "$code_url/tools/page_browser.php?mode=image&amp;project=$projectid&amp;imagefile=$imagefile";
echo "<p><a href='$url'>$url</a></p>";

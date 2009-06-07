<?
$relPath="./../../pinc/";
include($relPath.'site_vars.php');
include($relPath.'theme.inc');
include($relPath.'Project.inc');

$projectid = $_GET['project'];

$project = new Project($projectid);

$image_index_str = _('Image Index');

theme("$image_index_str: {$project->nameofwork}", 'header');

echo "
    <h1>{$project->nameofwork}</h1>
    <p>$projectid</p>
    <p><a href='$code_url/project.php?id=$projectid'>", _('Return to project page'), "</a></p>
    <h2>$image_index_str</h2>
    <P>Here are the individual images for this project:</P>
    <HR>
";

chdir("$projects_dir/$projectid");
foreach( glob("*.{png,jpg}", GLOB_BRACE) as $image_filename )
{
    $size = filesize($image_filename);
    $encoded_url = "$projects_url/$projectid/" . rawurlencode($image_filename);
    echo "<A HREF='$encoded_url'><B>$image_filename</B></A>";
    echo " <I>($size bytes)</I><BR>\n";
}

echo "
    <HR>
";

theme("", 'footer');

// vim: sw=4 ts=4 expandtab
?>

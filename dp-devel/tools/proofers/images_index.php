<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'theme.inc');

$projectid = $_GET['project'];

theme("Image Index", 'header');

echo "
    <HTML>
    <HEAD>  <TITLE>Image Index</TITLE>  </HEAD>
    <BODY>
    <H1 ALIGN=CENTER>Image Index</H1>
    <P>Here are the individual images for project $projectid:</P>
    <HR>
";

chdir($projects_dir);
foreach( glob("$projectid/*.png") as $png_relpath )
{
    $size = filesize($png_relpath);
    $png_filename = basename($png_relpath);
    echo "<A HREF='$projects_url/$png_relpath'><B>$png_filename</B></A>";
    echo " <I>($size bytes)</I><BR>\n";
}

echo "
    <HR>
    </BODY>
    </HTML>
";

theme("", 'footer');

?>

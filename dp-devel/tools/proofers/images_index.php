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

chdir("$projects_dir/$projectid");
foreach( glob("*.png") as $png_filename )
{
    $size = filesize($png_filename);
    $encoded_url = "$projects_url/$projectid/" . rawurlencode($png_filename);
    echo "<A HREF='$encoded_url'><B>$png_filename</B></A>";
    echo " <I>($size bytes)</I><BR>\n";
}

echo "
    <HR>
    </BODY>
    </HTML>
";

theme("", 'footer');

?>

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
$existing_image_names = glob("*.{png,jpg}", GLOB_BRACE);

list_images( $existing_image_names );

echo "
    <HR>
";

theme("", 'footer');

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function list_images( $image_names )
{
    echo "<table>\n";

    {
        echo "<tr>\n";
        echo "<th>", _('name'), "</th>\n";
        echo "<th>", _('size (bytes)'), "</th>\n";
        echo "</tr>\n";
    }

    foreach ( $image_names as $image_name )
    {
        echo "<tr>\n";

        {
            global $projects_url, $projectid;
            $encoded_url = "$projects_url/$projectid/" . rawurlencode($image_name);
            echo "<td><A HREF='$encoded_url'><B>$image_name</B></A></td>\n";

            $size = filesize($image_name);
            echo "<td align='right'>$size</td>\n";
        }

        echo "</tr>\n";
    }

    echo "</table>\n";
}

// vim: sw=4 ts=4 expandtab
?>

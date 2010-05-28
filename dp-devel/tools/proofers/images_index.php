<?php
$relPath="./../../pinc/";
include($relPath.'site_vars.php');
include($relPath.'theme.inc');
include($relPath.'Project.inc');

$projectid = validate_projectID('project', @$_GET['project']);

$project = new Project($projectid);

$image_index_str = _('Image Index');

theme("$image_index_str: {$project->nameofwork}", 'header');

echo "
    <h1>{$project->nameofwork}</h1>
    <p>$projectid</p>
    <p><a href='$code_url/project.php?id=$projectid'>", _('Return to project page'), "</a></p>
    <h2>$image_index_str</h2>
    <P>" . _('Below are the individual images for this project.') . "</P>
";

$page_image_names = array();
$res = mysql_query("
    SELECT image
    FROM $projectid
    ORDER BY image
") or die(mysql_error());
while ( list($image) = mysql_fetch_row($res) )
{
    $page_image_names[] = $image;
}

chdir("$projects_dir/$projectid");
$existing_image_names = glob("*.{png,jpg}", GLOB_BRACE);
// That returns a sorted list of the .png files
// followed by a sorted list of the .jpg files,
// but we want the two lists interleaved...
sort($existing_image_names);

$nonpage_image_names = array_diff($existing_image_names, $page_image_names);

echo "<table border='1'>\n";
echo "<tr>\n";
echo "<td valign='top'>\n";
list_images( $page_image_names, TRUE );
echo "</td>\n";
echo "<td valign='top'>\n";
list_images( $nonpage_image_names, FALSE );
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";

theme("", 'footer');

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function list_images( $image_names, $these_are_page_images )
{
    global $project, $existing_image_names;

    if ( $these_are_page_images )
    {
        $header = _('Page Images');
    }
    else
    {
        $header = _('Non-Page Images (Illustrations)');
    }
    echo "<h4 align='center'>$header</h4>";

    $show_replace_links = $project->can_be_managed_by_current_user;

    echo "<table>\n";

    {
        echo "<tr>\n";
        echo "<th>", _('name'), "</th>\n";
        echo "<th>", _('size (bytes)'), "</th>\n";
        if ( $show_replace_links )
        {
            echo "<th>", _('replace'), "</th>\n";
        }
        echo "</tr>\n";
    }

    foreach ( $image_names as $image_name )
    {
        echo "<tr>\n";

        if ( $these_are_page_images && !in_array( $image_name, $existing_image_names ) )
        {
            echo "<td>$image_name</td>\n";
            echo "<td bgcolor='#ff9999'>missing</td>\n";
        }
        else
        {
            global $projects_url, $projectid;
            $encoded_url = "$projects_url/$projectid/" . rawurlencode($image_name);
            echo "<td><A HREF='$encoded_url'><B>$image_name</B></A></td>\n";

            $size = filesize($image_name);
            echo "<td align='right'>$size</td>\n";
        }

        if ( $show_replace_links )
        {
            global $code_url;
            if ( $these_are_page_images)
            {
                $replace_url = "$code_url/tools/project_manager/handle_bad_page.php?projectid=$projectid&amp;image=$image_name&amp;modify=image";
            }
            else
            {
                $replace_url = "$code_url/tools/project_manager/replace_image.php?projectid=$projectid&amp;image=$image_name";
            }
            echo "<td><a href='$replace_url'>", _('replace'), "</a></td>\n";
        }

        echo "</tr>\n";
    }

    echo "</table>\n";
}

// vim: sw=4 ts=4 expandtab
?>
